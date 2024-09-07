<?php

if (!defined("ALLOW_INCLUDE"))
{
    exit;
}

function addCssFile(string $pathToFile) : void
{
    echo "<link rel=\"stylesheet\" href=\"" . $pathToFile . "?v=" . filemtime(ROOT_DIR . $pathToFile) . "\" />";
}

function addJsFile(string $pathToFile) : void
{
    echo "<script src=\"" . $pathToFile . "?v=" . filemtime(ROOT_DIR . $pathToFile) . "\"></script>";
}

function getQuestionsList() : array
{
    return [
        "В чём сила, брат?",
        "Жуки ползают или ходят лёжа?",
        "Почему меня зовут Семён?",
        "У меня заканчиваются идеи что запихать в эти опшны...",
        "Вы верите в Бога?",
        "Правильно творог или творог?",
        "Вы тоже прочитали опшн сверху с разными ударениями?"
    ];
}

function errorFor(string $fieldName) : void
{
    echo "<span class='feedback-form__error' id='error-for__$fieldName' style='opacity: 0;'>Поле обязательно для заполнения!</span>";
}

function label(string $labelName, bool $required = false) : void
{
    $requiredText = "<span class='feedback-form__required'>(обязательно)</span>";

    $labelValue = htmlspecialchars($labelName);

    $labelClasses = "feedback-form__label";
    if ($required)
        echo "<label class='$labelClasses' data-label-value='$labelValue'>$labelName $requiredText</label>";
    else
        echo "<label class='$labelClasses' data-label-value='$labelValue'>$labelName</label>";

}

function fieldStart(string $fieldName) : void
{
    echo "<div class='feedback-form__field' data-field-name='$fieldName'>";
}

function fieldEnd() : void
{
    echo "</div>";
}

function randColor($imgPng, ?int $red = null, ?int $green = null, ?int $blue = null)
{
    if ($red !== null && $green !== null && $blue !== null)
    {
        return imagecolorallocate($imgPng, $red, $green, $blue);
    }
    else
    {
        return imagecolorallocate($imgPng, rand(0, 255), rand(0, 255),rand(0, 255));
    }
}