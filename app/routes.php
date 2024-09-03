<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use FPDF as FPDF;
// require('setasign/fpdf');
// use Dotenv\Dotenv as env;
// $dotenv = env::createImmutable(__DIR__);
// $dotenv->load();

// $host='localhost';
// $port='5432';
// $name='trabajo';
// $user='postgres';
// $pass='root';

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/dbT', function (Request $request, Response $response) {

        $host = 'localhost';
        $dbname = 'trabajo';
        $user = 'root';
        $pass = '';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            // Crear una instancia de PDO
            $pdo = new PDO($dsn, $user, $pass);

            // Configurar el modo de error de PDO a excepciones
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //crear la consulta de los datos
            $consulta = "SELECT encargos.idEncargos, encargos.encargo, setup.descripcion, setup.valoracion FROM encargos, setup
                where encargos.idSetup = setup.idEquipo";

            // ejecuta la consulta
            $stmt = $pdo->query($consulta);

            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $respuesta = [
                'info' => $datos
            ];

            // $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            // print_r($respuesta);
            
            // echo $resultados;
            
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
            
        }
        
        return $response->getBody()->write(json_encode($respuesta));
        
    });

    $app->get('/saludo', function (Request $request, Response $response) {
        $queryParams = $request->getQueryParams();

        json_encode($queryParams);
        print_r($queryParams);
        $response->getBody()->write(json_encode([
            'contenidio' => $queryParams
        ]));
        // $response->getBody()->$request;
        // $response->getBody()->write('jala');

        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/pdf', function (Request $request, Response $response) {
        //todos los datos

        // datos generales
        $nombreC="Ian del Razo Cervantes";
        $categoria="Centers Developer";
        $cuentasColaborando="Cells BBVA";
        $telefono="5534522541";
        $img="../logs/perro.jpg";

        // datoslaborales
        $listaDatosLaborales=[[
            'nomOrg' => 'Universidad de Unam',
            'periodo' => '2000-3000',
            'puesto' => 'Patron',
            'actividades' => 'Dirigir a todos por que si',
            'tecnologias' => 'Software privado'
        ]];

        // datos academicos
        $nomPrograma="Ing es sistemas computacionales";
        $periodo="2000 a 3000";
        $institucion="CUGS";

        // certificaciones
        $listaCertificaciones=[[
            'nomCerti' => 'Full stack',
            'periCerti' => '2000-3000',
            'institucion' => 'Freecodecamp',
            'vigencia' => 'no aplica'
        ]];

        // cursos
        $listCursos=[[
            'nombre' => 'Java al 100%',
            'plataforma' => 'Undemy'
        ],[
            'nombre' => 'Mongo del 100%',
            'plataforma' => 'Undemy'
        ]];

        // idiomas
        $listIdiomas=[[
            'idioma' => 'Ingles','nivel' => 'A2'
        ],[
            'idioma' => 'Portuges','nivel' => 'B1'
        ]];

        $w=0;
        $w2=70;
        $w3=80;
        $h=8;
        $h2=6;

        // Elaboracion del pdf

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        // $pdf->SetMargins();
        $pdf->SetFont('Times', '', 16);
        $pdf->SetFillColor(24, 78, 180);
        $pdf->SetLineWidth(1);
        // $pdf->Cell(100,10,'Title',0,0,'C');
        $pdf->Ln(2);

        // datos generales
        // $pdf->Cell($w,10,'Datos generales.',0,1,'C');

        // $pdf->Cell($w,$h,'Numero de empleado: '.$numEmpleado,0,1,'L');
        $pdf->Cell($w,$h,$nombreC,0,1,'L');
        $pdf->Cell($w,$h,$categoria,0,1,'L');
        $pdf->Cell($w,$h,'Cuentas en las que ha colaborado en NTT Data: '.$cuentasColaborando,0,1,'L');
        $pdf->Cell($w,$h,'Telefono: '.$telefono,0,1,'L');
        $pdf->Image($img,150,20,40,30);
        $pdf->Ln(3);


        // $pdf->Cell($w,10,'Datos laborales',0,1,'C');
        $pdf->Ln(2);

        $pdf->SetFont('Arial', '', 14);


        foreach($listaDatosLaborales as $datoLaboral){

            // replicar dependencias
            $pdf->Cell($w,$h,'Nombre de organizacion:   '.$datoLaboral['nomOrg'],0,1,'L');
            $pdf->Cell($w,$h,'Periodo: '.$datoLaboral['periodo'],0,1,'L');
            $pdf->Cell($w,$h,'Puesto: '.$datoLaboral['puesto'],0,1,'L');
            $pdf->Cell($w,$h,'Actividades: '.$datoLaboral['actividades'],0,1,'L');
            $pdf->Cell($w,$h,'Tecnologias: '.$datoLaboral['tecnologias'],0,1,'L');
            $pdf->Ln(2);
        }

        $pdf->Ln(2);


        // $pdf->Cell($w,10,'Datos Acedemicos',0,1,'C');
        $pdf->Ln(1);

        $pdf->Cell($w,$h,'Nombre del programa: '.$nomPrograma,0,1);
        $pdf->Cell($w,$h,'Periodo: '.$periodo,0,1);
        $pdf->Cell($w,$h,'Institucion: '.$institucion,0,1);
        $pdf->Ln(2);


        // $pdf->Cell($w,$h,'Certificaciones',0,1,'C');
        $pdf->Ln(1);

        foreach($listaCertificaciones as $lineaCertificado){

            $pdf->Cell($w,$h,'Nombre de las certificaciones: '.$lineaCertificado['nomCerti'],0,1);
            $pdf->Cell($w,$h,'Periodo: '.$lineaCertificado['periCerti'] ,0,1);
            $pdf->Cell($w,$h,'Institucion: '.$lineaCertificado['institucion'] ,0,1);
            $pdf->Cell($w,$h,'Vigencia: '.$lineaCertificado['vigencia'] ,0,1);

        }

        $pdf->Ln(2);


        // $pdf->Cell($w,$h,'Cursos',0,1,'C');
        $pdf->Ln(2);

        $pdf->SetTextColor(235, 235, 235);
        $pdf->Cell($w2,$h2,'Nombre',1,0,'C',true);
        $pdf->Cell($w2,$h2,'Plataforma',1,1,'C',true);

        foreach($listCursos as $curso){
            $pdf->Cell($w2,$h2,$curso['nombre'],1,0,'C',true);
            $pdf->Cell($w2,$h2,$curso['plataforma'],1,1,'C',true);
        }

        // $pdf->Cell($w,$h,'',0,1,'L');
        $pdf->Ln(7);


        $pdf->SetTextColor(0, 0, 0);//texto claro

        // $pdf->Cell($w,$h,'Idiomas',0,1,'C');
        $pdf->Ln(1);
        
        $pdf->SetTextColor(235, 235, 235);//texto claro

        $pdf->Cell($w2,$h2,'Idioma',1,0,'C',true);
        $pdf->Cell($w2,$h2,'Nivel',1,1,'C',true);
        
        foreach($listIdiomas as $idioma){
            
            $pdf->Cell($w2,$h2,$idioma['idioma'],1,0,'C',true);
            $pdf->Cell($w2,$h2,$idioma['nivel'],1,1,'C',true);
        }
        $pdf->Ln(3);



        // $pdf->Cell(60,10,'Hecho con FPDF.',0,1,'C');
        // $pdf->Output();

        $pdfContent = $pdf->Output('S');

        // Establece los encabezados para la respuesta HTTP
        $response = $response->withHeader('Content-Type', 'application/pdf')->withHeader('Content-Disposition', 'attachment; filename="archivo.pdf"')->withHeader('Content-Length', strlen($pdfContent));

        // Escribe el contenido del PDF en la respuesta
        $response->getBody()->write($pdfContent);

        return $response;
    });

    $app->get('/variables',function (Request $request, Response $response){

        $host='localhost';
        $port='5432';
        $name='trabajo';
        $user='postgres';
        $pass='root';

        $datos = [
            'respuesta' => [
                'host' => $host,
                'port' => $port,
                'name' => $name,
                'user' => $user,
                'pass' => $pass
            ]
        ];


        $response = $response->withHeader('Content-Type','application/json');

        $response->getBody()->write(json_encode($datos));

        return $response;

        // $response->getBody()->write(json_encode([
        //     'host'=>$_SERVER['DB_HOST'],
        //     'port'=>$_SERVER['DB_PORT'],
        //     'name'=>$_SERVER['DB_NAME'],
        //     'user'=>$_SERVER['DB_USER'],
        //     'passs'=>$_SERVER['DB_PASS']
        // ]));
        /* $response = [
            'host'=>$_ENV['DB_HOST'],
            'port'=>$_ENV['DB_PORT'],
            'name'=>$_ENV['DB_NAME'],
            'user'=>$_ENV['DB_USER'],
            'passs'=>$_ENV['DB_PASS'],
        ]; */

        // json_encode($response);

        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
