<?php

namespace Application\Controllers;

use System\Base\Request;
use System\View;
use Application\Models\News;
use Application\Models\Review;
use Application\Classes\Pagination;
use Application\Models\Subject;
use System\Helpers\FileHelper;
use Application\Classes\Captcha;

class indexController {
    public function actionIndex()
    {
        /*
         * Параметры для сортировки по умолчанию
         */
        $sort = 'ASC';
        $limit = 7;
        $currentPage = 1;

        $subjects = new Subject();
        $allSubjects = $subjects->getAllSubjects();

        $totalSubjectsGroup = $subjects->getGroupCountSubject();

        $reviews = new Review();
        $totalReviews = $reviews->getCountReviews();

        $analyticsAll = $reviews->getAnalytics($totalSubjectsGroup);

        $analytics = $analyticsAll['analytics'];

        $likes = $analyticsAll['likes'];
        $improves = $analyticsAll['improves'];
        $timeToChange = $analyticsAll['time_to_change'];
        $complaints = $analyticsAll['complaints'];

        if (isset($_GET['date']) and $_GET['date'] == 'up') {
            $sort = 'DESC';
        }

        if (isset($_GET['limit'])) {
            switch ($_GET['limit']) {
                case 14:
                    $limit = 14;
                    break;
                case 21:
                    $limit = 21;
                    break;
                case 70:
                    $limit = 70;
                    break;
            }
        }

        if (isset($_GET['page']) and ctype_digit($_GET['page'])) {
            if (ceil($totalReviews / $limit) >= $_GET['page'] && $_GET['page'] != 0) {
                $currentPage = $_GET['page'];
            }
        }

        parse_str($_SERVER['QUERY_STRING'], $queryVars );

        if (!isset($queryVars['date']) || $queryVars['date'] == 'down') {
            $queryVars['date'] = 'up';
        } elseif (isset($queryVars['date']) && $queryVars['date'] == 'up') {
            $queryVars['date'] = 'down';
        }

        $linkSortDate = http_build_query($queryVars);

        $linkSortLimit7  = $this->createLinkLimit(7);
        $linkSortLimit14  = $this->createLinkLimit(14);
        $linkSortLimit21  = $this->createLinkLimit(21);
        $linkSortLimit70  = $this->createLinkLimit(70);

        $allReviews = $reviews->getAllReviews($currentPage, $limit, $sort);

        $pagination = (new Pagination($totalReviews, $currentPage, $limit, 'page'))->get();

        View::render('index',[
            'allSubjects' => $allSubjects,
            'allReviews' => $allReviews,
            'linkSortDate' => $linkSortDate,
            'linkSortLimit7' => $linkSortLimit7,
            'linkSortLimit14' => $linkSortLimit14,
            'linkSortLimit21' => $linkSortLimit21,
            'linkSortLimit70' => $linkSortLimit70,
            'pagination' => $pagination,
            'totalReviews' => $totalReviews,
            'analytics' => $analytics,
            'likes' => $likes,
            'improves' => $improves,
            'timeToChange' => $timeToChange,
            'complaints' => $complaints,
        ]);
    }

    protected function createLinkLimit($newLimit)
    {
        parse_str($_SERVER['QUERY_STRING'], $queryVars );

        $queryVars['page'] = 1;
        $queryVars['limit'] = $newLimit;

        $link = http_build_query($queryVars);

        return $link;
    }

    public function actionAdd()
    {
        if (Request::isAjax() && Request::isPost()) {
            try {
                $captcha = new Captcha();

                $isValidCaptcha = $captcha->validateCaptcha($_POST['captcha']);

                if ($isValidCaptcha) {
                    $isValidCaptchaTime = $captcha->validateCaptchaTime();

                    if (!$isValidCaptchaTime) {
                        throw new \Exception(serialize(['code' => 2, 'message' => 'Время истекло. Введите новую каптчу']));
                    }

                    $fullName = $_POST['full_name'];
                    $subject = $_POST['subject'];
                    $description = $_POST['description'];
                    $image = null;

                    if (empty($fullName) && empty($subject) && empty($description)) {
                        throw new \Exception(serialize(['message' => 'Заполните все обязательные поля']));
                    }

                    $imgExt = ['image/jpeg', 'image/png'];

                    $filePath = $_FILES['image']['tmp_name'];
                    $errorCode = $_FILES['image']['error'];

                    if ($errorCode === UPLOAD_ERR_OK && is_uploaded_file($filePath)) {

                        $fi = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = (string)finfo_file($fi, $filePath);

                        if (in_array($mime, $imgExt)) {
                            $ext = explode('/', $mime);
                            $ext = $ext['1'] == 'jpeg' ? 'jpg' : 'png';

                            $image = FileHelper::getRandomFileName('Uploads/images', $ext);
                            $image .= '.' . $ext;

                            if (!move_uploaded_file($filePath, 'Uploads/images/' . $image)) {
                                throw new \Exception(serialize(['message' => 'Что-то пошло не так. Обратитесь к администратору']));
                            }
                        } else {
                            throw new \Exception(serialize(['message' => 'Допустимый формат JPG и PNG']));
                        }
                    }

                    $review = new Review();
                    $newReview = $review->addReview($fullName, $subject, $description, $image);

                    if (!$newReview) {
                        throw new \Exception('Что-то пошло не так');
                    }

                    echo json_encode(['code' => 1, 'message' => 'Отзыв успешно добавлен']);
                } else {
                    echo json_encode(['code' => 2, 'message' => 'Не верно введён код. Введите новую каптчу']);
                }
            } catch (\Exception $e) {
                $data = unserialize($e->getMessage());
                $code = $data['code'] ?? 0;
                echo json_encode(['code' => $code, 'message' => $data['message']]);
            }
        }
    }

    public function actionCaptcha()
    {
        session_start();

        $captcha = new Captcha();

        $captcha_code = $captcha->getCaptchaCode(6);
        $captcha->setSession('captcha_code', $captcha_code);
        $imageData = $captcha->createCaptchaImage($captcha_code);
        $captcha->renderCaptchaImage($imageData);
    }

    public function actionLike()
    {
        if (Request::isAjax() && Request::isPost()) {
            if (isset($_POST['like']) and isset($_POST['id'])) {
                $review = new Review();
                $review->like($_POST['id']);
            }
        }
    }
}