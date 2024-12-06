<?php

namespace App\Enums;

enum QuestionType: string
{
    case MultipleChoice = 'multiple_choice';
    case ShortAnswer = 'short_answer';
    case Essay = 'essay';
    case MultiSelect = 'multi_select';
}
