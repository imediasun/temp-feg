<?php
/*
 * PHP QR Code encoder
 *
 * Image output of code using GD2
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
 
    define('QR_IMAGE', true);

    class QRimage {
        
        public static $black = array(255,255,255);
        public static $white = array(0,0,0);

        //----------------------------------------------------------------------
        public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4,$saveandprint=FALSE) 
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame);
            
            if ($filename === false) {
                Header("Content-type: image/png");
                ImagePng($image);
            } else {
                if($saveandprint===TRUE){
                    ImagePng($image, $filename);
                    header("Content-type: image/png");
                    ImagePng($image);
                }else{
                    ImagePng($image, $filename);
                }
            }
            
            ImageDestroy($image);
        }
    
        //----------------------------------------------------------------------
        public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85) 
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame);
            
            if ($filename === false) {
                Header("Content-type: image/jpeg");
                ImageJpeg($image, null, $q);
            } else {
                ImageJpeg($image, $filename, $q);            
            }
            
            ImageDestroy($image);
        }
    
        //----------------------------------------------------------------------
        private static function image($frame, $pixelPerPoint = 4, $outerFrame = 4) 
        {
            $h = count($frame);
            $w = strlen($frame[0]);
            
            $imgW = $w + 4*$outerFrame;
            $imgH = $h + 4*$outerFrame + 4; 
            
            $base_image =ImageCreate($imgW, $imgH);
            
            $col[0] = ImageColorAllocate($base_image,QRImage::$black[0],QRImage::$black[1],QRImage::$black[2]);
            $col[1] = ImageColorAllocate($base_image,QRImage::$white[0],QRImage::$white[1],QRImage::$white[2]);

            imagefill($base_image, 0, 0, $col[0]);

            for($y=0; $y<$h; $y++) {
                for($x=0; $x<$w; $x++) {
                    if ($frame[$y][$x] == '1') {
                        ImageSetPixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]); 
                    }
                }
            }
		  
            $target_image =ImageCreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
	 		
            ImageCopyResized($target_image, $base_image, 10, 5, 0, 0, $imgW * $pixelPerPoint * .95, $imgH * $pixelPerPoint * .95, $imgW, $imgH);
			
			$color_pink = ImageColorAllocate($target_image, 255, 20, 147); 
            $color_white = ImageColorAllocate($target_image, 255, 255, 255); 
			$x1 = 0; 
			$y1 = 0; 
			$x2 = ImageSX($target_image) - 1; 
			$y2 = ImageSY($target_image) - 1; 
		
			for($i = 0; $i < 2; $i++) 
			{ 
				ImageRectangle($target_image, $x1++, $y1++, $x2--, $y2--, $color_white); 
			}
			
            ImageDestroy($base_image);
            
            return $target_image;
        }
		
		
		// Draw a border 
		public static function drawBorder($img, $color, $thickness = 1) 
		{ 
			$x1 = 0; 
			$y1 = 0; 
			$x2 = ImageSX($img) - 1; 
			$y2 = ImageSY($img) - 1; 
		
			for($i = 0; $i < $thickness; $i++) 
			{ 
				ImageRectangle($img, $x1++, $y1++, $x2--, $y2--, $color); 
			} 
		} 
    }