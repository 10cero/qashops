<?php

namespace App\Controller;

use App\Borme;

// 		$url = "https://www.boe.es/borme/dias/2017/01/10/pdfs/BORME-A-2017-6-41.pdf";

class BormeDownloader
{

	public function __construct()
	{
		if ($_POST['ajax']) {

			$response = $this->downloadBorme($_POST['url']);
			echo json_encode($response);
			die();
		}
        else if($_POST['action']) {

          if($_POST['action']=="ShowTxt") {
            $response = $this->showTxt();
            echo json_encode($response);
          }
          else if ($_POST['action']=="ShowPdf"){
            $response = $this->showPdf();
            echo json_encode($response);
          }

        } else{
			$this->showForm();
		}
	}

	public function showForm()
	{

		$html = '<!doctype html>
			<html lang="es">
			<head>
				<link href="views/css/bootstrap.min.css" rel="stylesheet">
				<link href="views/css/style.css" rel="stylesheet">
			</head>
			<body>
              <div class="container">
                <header class="blog-header py-3">
                  <div class="row flex-nowrap justify-content-between align-items-center">
                    <div class="col-4 pt-1">
                      BORME for Qashops
                    </div>
                  </div>
                </header>

                <div class="row mb-2">
                  <div class="col-md-6">
                    <div class="card flex-md-row mb-4 box-shadow h-md-250">
                    <form name="download_borme" id="download_borme" method="post" style="width:100%">
                        <input type="hidden" name="ajax" value="1">
                        <div class="card-body d-flex flex-column align-items-start">
                          <strong class="d-inline-block mb-2 text-primary">Área de descarga</strong>
                          <div class="mb-3" style="width:100%">
                            <label for="username">Url de documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="url" id="url" placeholder="https://..." required="">
                            </div>
                          </div>
                          <div class="mb-3">
                            <div class="input-group">
                                <button class="btn btn-outline-primary">Descargar</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="col-md-6" >
                    <div class="card flex-md-row mb-4 box-shadow h-md-250" style="height: 210px; overflow:auto">
                      <div class="card-body  align-items-start" id="notifications" >
                        <strong class="d-inline-block mb-2 text-primary">Notificaciones</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6" >
                    <div class="card flex-md-row mb-4 box-shadow h-md-250" style="height: 210px; overflow:auto">
                      <div  class="card-body d-flex flex-column align-items-start">
                        <strong class="d-inline-block mb-2 text-primary">Ficheros procesados</strong>
                        <div id="txt-results"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6" >
                    <div class="card flex-md-row mb-4 box-shadow h-md-250" style="height: 210px; overflow:auto">
                      <div class="card-body d-flex flex-column align-items-start">
                        <strong class="d-inline-block mb-2 text-primary">Ficheros fallidos</strong>
                        <div id="pdf-results"></div>
                      </div>
                    </div>
                  </div>
                </div>
              <footer class="blog-footer">
                <p>
                  <a href="#">Volver arriba</a>
                </p>
              </footer>
              </div>
              <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    		  <script src="views/js/bootstrap.min.js"></script>
    		  <script src="views/js/init.js"></script>
			</body>';

		echo $html;

	}

	public function downloadBorme($url)
	{
		if (Borme::urlExists($url)) {

            $retry = 0;
            $filePdf = NULL;
			while($retry < RETRY_MAX AND $filePdf==NULL) {
                $filePdf = Borme::downloadPdf($url);
            }

			if ($filePdf) {
				$fileTxt = Borme::generateTxt($filePdf);
				$respuesta['success'] = $fileTxt;
			} else {
				$respuesta['error'] = "Pdf no convertible (" . $filePdf . ")";
			}

		} else {
			$respuesta['error'] = "Url incorrecta (" . $url . ")";
		}

		return $respuesta;
	}

	public function showTxt()
	{
        $files = scandir ( TXT_DIR );

        $result = array();
        foreach($files as $file)
        {
            if(strpos($file,'.txt'))
            {
                $result['files'][] = $file;
            }
        }

        echo json_encode($result);
        die();
	}

  public function showPdf()
  {
    $files = scandir ( PDF_DIR );


    $result = array();
    foreach($files as $file)
    {
      if(strpos($file,'.pdf'))
      {
        $result['files'][] = $file;
      }
    }


    echo json_encode($result);
    die();
  }

}