<?php

namespace App\Enums;

enum MaterialType: string
{
    case Video = 'video';
    case PDF = 'pdf';
    case PPT = 'ppt';
    case Docs = 'docs';
}
