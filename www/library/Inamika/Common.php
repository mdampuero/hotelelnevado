<?php
function is_valid_image($file) {
    $extension=strtolower(end(explode(".", $file)));
    switch (strtoupper($extension)) {
        case "JPG":
            return $extension;
            break;
        case "PNG":
            return 'png';
            break;
        case "GIF":
            return 'gif';
            break;
        case "JPEG":
            return 'jpg';
            break;
        default:
            return false;
            break;
    }
}

function smart_resize_image( $file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false )
  {
    if ( $height <= 0 && $width <= 0 ) {
      return false;
    }

    $info = getimagesize($file);
    $image = '';

    $final_width = 0;
    $final_height = 0;
    list($width_old, $height_old) = $info;

    if ($proportional) {
      if ($width == 0) $factor = $height/$height_old;
      elseif ($height == 0) $factor = $width/$width_old;
      else $factor = min ( $width / $width_old, $height / $height_old);   

      $final_width = round ($width_old * $factor);
      $final_height = round ($height_old * $factor);

    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
    }

    switch ( $info[2] ) {
      case IMAGETYPE_GIF:
        $image = imagecreatefromgif($file);
      break;
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($file);
      break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($file);
      break;
      default:
        return false;
    }
    
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
        
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $trnprt_indx = imagecolortransparent($image);
   
      // If we have a specific transparent color
      if ($trnprt_indx >= 0) {
   
        // Get the original image's transparent color's RGB values
        $trnprt_color    = imagecolorsforindex($image, $trnprt_indx);
   
        // Allocate the same color in the new image resource
        $trnprt_indx    = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
   
        // Completely fill the background of the new image with allocated color.
        imagefill($image_resized, 0, 0, $trnprt_indx);
   
        // Set the background color for new image to transparent
        imagecolortransparent($image_resized, $trnprt_indx);
   
      
      } 
      // Always make a transparent background color for PNGs that don't have one allocated already
      elseif ($info[2] == IMAGETYPE_PNG) {
   
        // Turn off transparency blending (temporarily)
        imagealphablending($image_resized, false);
   
        // Create a new transparent color for image
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
   
        // Completely fill the background of the new image with allocated color.
        imagefill($image_resized, 0, 0, $color);
   
        // Restore transparency blending
        imagesavealpha($image_resized, true);
      }
    }

    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
  
    if ( $delete_original ) {
      if ( $use_linux_commands )
        exec('rm '.$file);
      else
        @unlink($file);
    }
    
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }

    switch ( $info[2] ) {
      case IMAGETYPE_GIF:
        imagegif($image_resized, $output);
      break;
      case IMAGETYPE_JPEG:
        imagejpeg($image_resized, $output);
      break;
      case IMAGETYPE_PNG:
        imagepng($image_resized, $output);
      break;
      default:
        return false;
    }

    return true;
  }

//----------------------------------------------//
//FUNCION QUE ARMA QUERY PARA BUSCAR RECIBE
//UN STRIGN QUE LO PARSEA POR ESPACIO Y ARRAY DE CAMPOS A BUSCAR
//----------------------------------------------//
function create_where($words, $fields) {
    $words = explode(' ', $words);
    foreach ($words as $word) {
        foreach ($fields as $field) {
            if ($field['search'] == true) {
                $str_query.=$field["field"] . " LIKE '%" . $word . "%' or ";
            }
        }
    }
    $str_query = substr($str_query, 0, -3);
    $str_query = "(" . $str_query . ")";
    return $str_query;
}

//----------------------------------------------//
//FUNCION REDIMENSIONAR Y CAMBIAR DE LUGAR UNA IMAGEN
//----------------------------------------------//

function invert_date($date,$lang)
    {
        if($lang=="spanish"){ 
            $year=substr($date,0,4); 
            $month=substr($date,5,2); 
            $day=substr($date,8,2); 
            $newdate="$day-$month-$year"; 
 }elseif($lang=="english"){ 
            $year=substr($date,6,4); 
            $month=substr($date,3,2); 
            $day=substr($date,0,2); 
            $newdate="$year-$month-$day"; 
 }
        return $newdate;
    }

//****************************************
//FUNCION PARA MOSTRAR UN MES EN LETRAS
//****************************************
function month_in_letters($month, $languaje = 'spa') {
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);
    switch ($month) {        
        case '01': ($languaje == 'spa') ? $month_letters = 'Enero' : $month_letters = 'January';
            break;
        case '02': ($languaje == 'spa') ? $month_letters = 'Febrero' : $month_letters = 'February';
            break;
        case '03': ($languaje == 'spa') ? $month_letters = 'Marzo' : $month_letters = 'March';
            break;
        case '04': ($languaje == 'spa') ? $month_letters = 'Abril' : $month_letters = 'April';
            break;
        case '05': ($languaje == 'spa') ? $month_letters = 'Mayo' : $month_letters = 'May';
            break;
        case '06': ($languaje == 'spa') ? $month_letters = 'Junio' : $month_letters = 'June';
            break;
        case '07': ($languaje == 'spa') ? $month_letters = 'Julio' : $month_letters = 'July';
            break;
        case '08': ($languaje == 'spa') ? $month_letters = 'Agosto' : $month_letters = 'August';
            break;
        case '09': ($languaje == 'spa') ? $month_letters = 'Septiembre' : $month_letters = 'September';
            break;
        case '10': ($languaje == 'spa') ? $month_letters = 'Octubre' : $month_letters = 'October';
            break;
        case '11': ($languaje == 'spa') ? $month_letters = 'Noviembre' : $month_letters = 'November';
            break;
        case '12': ($languaje == 'spa') ? $month_letters = 'Diciembre' : $month_letters = 'December';
            break;
    }
    return $month_letters;
}

function day_in_letters($month, $languaje) {
    switch ($month) {
        case 'Mon': ($languaje == 'spa') ? $month_letters = 'Lunes' : $month_letters = 'Monday';
            break;
        case 'Tue': ($languaje == 'spa') ? $month_letters = 'Martes' : $month_letters = 'Tuesday';
            break;
        case 'Wed': ($languaje == 'spa') ? $month_letters = 'Miercoles' : $month_letters = 'Wednesday';
            break;
        case 'Thu': ($languaje == 'spa') ? $month_letters = 'Jueves' : $month_letters = 'Thursday';
            break;
        case 'Fri': ($languaje == 'spa') ? $month_letters = 'Viernes' : $month_letters = 'Friday';
            break;
        case 'Sat': ($languaje == 'spa') ? $month_letters = 'Sabado' : $month_letters = 'Saturday';
            break;
        case 'Sun': ($languaje == 'spa') ? $month_letters = 'Domingo' : $month_letters = 'Sunday';
            break;
    }
    return $month_letters;
}
//----------------------------------------------//
//FUNCION CORTAR TEXTOS LARGOS Y AGREAGR PUNTOS
//----------------------------------------------//
function CutText($text_to_cut, $words_to_display) {
    $text_to_cut = strip_tags($text_to_cut);
    //Checking main argouments phase 1 
    if (trim($text_to_cut) == "") {
        $text_cutted = "";
        return false;
    }

    //Checking main argouments phase 2 
    if ($text_to_cut == "" && (!is_numeric($words_to_display))) {
        $text_cutted = "";
        return false;
    } else {
        $words_to_display = (integer) $words_to_display;
    }

    //Cutting text 
    $vectors = explode(" ", $text_to_cut);
    if ($words_to_display >= (count($vectors) + 1)) {

        return $text_to_cut;
    } else {
        $LimUP = ($words_to_display - 1);
        $exitLoop = false;
        do {
            $c = substr($vectors[$LimUP], strlen($vectors[$LimUP]) - 1, 1);
            if (($c == ",") or ($c == ".") or ($c == ";") or ($c == ":") or
                    ($c == "+") or ($c == "-") or ($c == "@")) {

                $LimUP-=1;
            } else {
                $exitLoop = true;
            }
        } while ($exitLoop = false and $LimUP >= 0);
        $FinalText = "";
        for ($i = 0; $i <= $LimUP; $i++) {
            $FinalText.=$vectors[$i] . " ";
        }
        $FinalText.="...";
        return $FinalText;
    }
}
?>
