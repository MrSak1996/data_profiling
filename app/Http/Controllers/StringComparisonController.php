<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StringComparisonController extends Controller
{
    public function getJaroWinkler($str1, $str2)
    {
        $jaro = $this->getJaroSimilarity($str1, $str2);

        $prefixLength = 0;
        $maxPrefix = 4; // Max prefix length for Jaro-Winkler

        $len1 = min($maxPrefix, strlen($str1));
        $len2 = min($maxPrefix, strlen($str2));

        for ($i = 0; $i < $len1; $i++) {
            if ($str1[$i] === $str2[$i]) {
                $prefixLength++;
            } else {
                break;
            }
        }

        $scalingFactor = 0.1; // Standard scaling factor for Jaro-Winkler
        return $jaro + ($prefixLength * $scalingFactor * (1 - $jaro));
    }

    public function getJaroSimilarity($str1, $str2)
    {
        $str1Len = strlen($str1);   
        $str2Len = strlen($str2);

        if ($str1Len === 0 || $str2Len === 0) {
            return 0.0;
        }

        $matchDistance = (int)floor(max($str1Len, $str2Len) / 2) - 1;

        $str1Matches = array_fill(0, $str1Len, false);
        $str2Matches = array_fill(0, $str2Len, false);

        $matches = 0;

        // Count matches
        for ($i = 0; $i < $str1Len; $i++) {
            $start = max(0, $i - $matchDistance);
            $end = min($i + $matchDistance + 1, $str2Len);

            for ($j = $start; $j < $end; $j++) {
                if (!$str2Matches[$j] && $str1[$i] === $str2[$j]) {
                    $str1Matches[$i] = true;
                    $str2Matches[$j] = true;
                    $matches++;
                    break;
                }
            }
        }

        if ($matches === 0) {
            return 0.0;
        }

        // Count transpositions
        $transpositions = 0;
        $k = 0;

        for ($i = 0; $i < $str1Len; $i++) {
            if ($str1Matches[$i]) {
                while (!$str2Matches[$k]) {
                    $k++;
                }
                if ($str1[$i] !== $str2[$k]) {
                    $transpositions++;
                }
                $k++;
            }
        }

        $transpositions /= 2.0;

        // Calculate the Jaro similarity
        return (($matches / $str1Len) + ($matches / $str2Len) + (($matches - $transpositions) / $matches)) / 3.0;
    }
    
}

