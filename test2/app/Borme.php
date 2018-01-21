<?php
/**
 * Created by PhpStorm.
 * User: Francisco Torres
 * Date: 21/01/2018
 * Time: 11:47
 */

namespace App;

use Smalot\PdfParser as PdfParser;

class Borme
{

	public static function downloadPdf(
		$url
	)
	{
		$filename = basename($url);
		$destination = PDF_DIR . $filename;

		$file = fopen($destination, "w+");

		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FILE, $file);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 1);
			curl_exec($ch);
			curl_close($ch);
		} catch (Exception $e) {
			return NULL;
		}

		fclose($file);

		return ($filename);

	}

	public static function urlExists(
		$url
	)
	{
		if (empty($url)) {
			return false;
		}

		$ch = curl_init($url);

		//Establecer un tiempo de espera
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

		//establecer NOBODY en true para hacer una solicitud tipo HEAD
		curl_setopt($ch, CURLOPT_NOBODY, true);
		//Permitir seguir redireccionamientos
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//recibir la respuesta como string, no output
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$data = curl_exec($ch);

		//Obtener el código de respuesta
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//cerrar conexión
		curl_close($ch);

		//Aceptar solo respuesta 200 (Ok), 301 (redirección permanente) o 302 (redirección temporal)
		$accepted_response = array(200, 301, 302);

		if (in_array($httpcode, $accepted_response)) {
			return true;
		} else {
			return false;
		}
	}

	public static function generateTxt(
		$filePdf
	)
	{
		$origin = PDF_DIR . $filePdf;

		$parser = new PdfParser\Parser();
		$pdf = $parser->parseFile($origin);
		$text = $pdf->getText();


		$fileTxt = str_replace('.pdf', '.txt', $filePdf);
		$target = TXT_DIR . $fileTxt;

		$file = fopen($target, "w");
		if (fwrite($file, $text)) {
			self::deletePdf($filePdf);
			return $fileTxt;
		} else
			return false;
	}

	public static function deletePdf(
		$filePdf
	)
	{
		$target = PDF_DIR . $filePdf;

		return unlink($target);
	}


}

?>