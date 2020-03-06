<?php

namespace Application\Models;

use System\Base\Model;
use PDO;

class Review extends \System\Base\Model {
    public function getAllReviews($startPage = 1, $limit = 7, $sort = 'ASC')
    {
        $query = $this->db->prepare("
            SELECT *,review.id AS review_id FROM review INNER JOIN subject 
            ON review.subject_id = subject.id
            ORDER BY date {$sort}
            LIMIT :limit
            OFFSET :offset
        ");

        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->bindValue(':offset', ($startPage - 1) * $limit, PDO::PARAM_INT);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountReviews()
    {
        $query = $this->db->query('SELECT COUNT(*) FROM review');

        return $query->fetchColumn();
    }

    public function addReview($fullName, $subjectId, $description, $image)
    {
        $query = $this->db->prepare("
            INSERT INTO review
            (`full_name`, `subject_id`, `description`, `image`, `date`)
            VALUES
            (:fullName, :subjectId, :description, :image, NOW())
        ");

        $query->bindValue(':fullName', $fullName, PDO::PARAM_STR);
        $query->bindValue(':subjectId', $subjectId, PDO::PARAM_INT);
        $query->bindValue(':description', $description, PDO::PARAM_STR);
        $query->bindValue(':image', $image, PDO::PARAM_STR);

        return $query->execute();
    }

    /*
     *   Благодарность — +1 к любви, 0 к улучшениям, 0 к переменам, -1 к огню(Надо сжечь это место)
     *   Предложения к улучшению — 0 / 1 / 1 / 0
     *   Жалоба — -1 / 0 / 1 / 1
     *
     *   Выбираем что собрало больше всего балов. Если количество баллов одинаковое,
     *   что может быть когда мало отзывов. То выводим - Не достаточно данных.
     */
    public function getAnalytics($totalSubjectsGroup)
    {
        $likes = 0;
        $improves = 0;
        $timeToChange = 0;
        $complaints = 0;

        foreach ($totalSubjectsGroup as $subjectCount) {
            switch ($subjectCount['subject_id']) {
                case 1:
                    $likesCount = $subjectCount['count'];
                    break;
                case 2:
                    $improvesLikes = $subjectCount['count'];
                    break;
                case 3:
                    $complaintsCounts = $subjectCount['count'];
                    break;
            }
        }

        if (isset($likesCount)) {
            $likes += $likesCount;
            $complaints -= $likesCount;
        }

        if (isset($improvesLikes)) {
            $improves += $improvesLikes;
            $timeToChange += $improvesLikes;
        }

        if (isset($complaintsCounts)) {
            $likes -= $complaintsCounts;
            $timeToChange += $complaintsCounts;
            $complaints += $complaintsCounts;
        }

        if ($likes > max($improves, $timeToChange, $complaints)) {
            $analytics = 'Клиенты нас любят';
        } elseif ($improves > max($likes, $timeToChange, $complaints)) {
            $analytics = 'Нам надо совершенствоваться';
        } elseif ($timeToChange > max($likes, $timeToChange, $complaints)) {
            $analytics = 'Пора меняться';
        } elseif ($complaints > max($likes, $timeToChange, $complaints)) {
            $analytics = 'Надо сжечь это место';
        } else {
            $analytics = 'Не достаточно данных';
        }

        return [
            'likes' => $likes,
            'improves' => $improves,
            'time_to_change' => $timeToChange,
            'complaints' => $complaints,
            'analytics' => $analytics,
        ];
    }

    public function like($id) {
        $query = $this->db->prepare('UPDATE review SET likes = likes + 1 WHERE id=:id');

        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
}

