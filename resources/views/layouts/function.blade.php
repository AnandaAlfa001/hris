<?php
      function myDate($time) {
        if ($time == '0000-00-00') {
          return "-";
        }
        elseif($time == '') {
        	return "-";
        } 
        else {
          return date("D, d M Y", strtotime($time));
        }
      }

      function TglLahir($time) {
        if ($time == '0000-00-00') {
          return "-";
        }
        elseif($time == '') {
          return "-";
        } 
        else {
          return date("d F Y", strtotime($time));
        }
      }

      function indonesiaDate($date="") {
        if ($date == '' or $date == null or $date == '0000-00-00') {
          return $date;
        }else{ 
         $months = [
         'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
         ];
         $dates = explode('-', $date);
         return $dates[2] . ' ' . $months[intval($dates[1]) - 1] . ' ' . $dates[0];
        }
      }
    ?>