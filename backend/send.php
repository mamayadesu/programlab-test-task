<?php

define("ALLOW_INCLUDE", true);
require "functions.php";

session_start();

$result = [
    "success" => true,
    "error" => ""
];

if (!isset($_POST["question_is_about"]) || $_POST["question_is_about"] === "")
{
    $_POST["question_is_about"] = "-1";
}

$question_is_about = $_POST["question_is_about"] ?? "";
$question = $_POST["question"] ?? "";
$name = $_POST["name"] ?? "";
$phone = $_POST["phone"] ?? "";
$email = $_POST["email"] ?? "";
$organisation = $_POST["organisation"] ?? "";
$job = $_POST["job"] ?? "";
$captcha = $_POST["captcha"] ?? "";

if ($captcha != $_SESSION["captcha"] || $_SESSION["captcha"] == "" || $_SESSION["captcha"] == "nocaptcha")
{
    $result["success"] = false;
    $result["error"] = "Проверочный код с картинки введён неверно";
    $_SESSION["captcha"] = "nocaptcha";
    die(json_encode($result));
}

$errors = "";

if ($question_is_about == "-1")
{
    $errors .= "Не выбрана тема вопроса. ";
}

if (strlen($question) > 4096)
{
    $errors .= "Вопрос слишком длинный. ";
}

if (strlen(trim($question)) == 0)
{
    $errors .= "Вопрос не указан. ";
}

if (strlen($name) > 255)
{
    $errors .= "Имя слишком длинное. ";
}

if (strlen(trim($question)) == 0)
{
    $errors .= "Имя не указано. ";
}

if (strlen($email) > 255)
{
    $errors .= "E-Mail слишком длинный. ";
}

if (strlen(trim($email)) == 0)
{
    $errors .= "E-Mail не указан. ";
}

if (strlen($organisation) > 255)
{
    $errors .= "Название организации слишком длинное. ";
}

if (strlen($job) > 255)
{
    $errors .= "Название профессии слишком длинное. ";
}

$raw_phone_number = str_replace([" ", "+", "(", ")", "-"], ["", "", "", "", ""], $phone);

if (!preg_match("/^[0-9]{11}/", $raw_phone_number))
{
    $errors .= "Неверный номер телефона. ";
}

if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email))
{
    $errors .= "E-Mail указан неверно. ";
}

if (strlen($errors) > 0)
{
    $result["success"] = false;
    $result["error"] = $errors;
    die(json_encode($result));
}

$question_mail = nl2br($question);

$mail_body = <<<HTML
Здравствуйте, $name!<br><br>

Вы оставляли свой вопрос на форме обратной связи. Ваш вопрос: <br><br>

<blockquote>$question_mail</blockquote>
HTML;

$headers = "From: admin@xrefcore.ru\r\n" .
    "Reply-To: admin@xrefcore.ru\r\n" .
    "X-Mailer: PHP/" . phpversion();

$email_sending_result = @mail($email, "Ваш вопрос о " . getQuestionsList()[$question_is_about], $mail_body, $headers);

if (!$email_sending_result)
{
    $result["success"] = false;
    $result["error"] = "Не удалось отправить письмо. Почтовый сервер не настроен.";
    die(json_encode($result));
}

die(json_encode($result));