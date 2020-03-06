<!DOCTYPE html>
<html lang="en" >
    <head>
      <meta charset="UTF-8">
      <title>CodePen - Building Responsive Forms With Flexbox</title>
      <link href='https://fonts.googleapis.com/css?family=Fira+Sans:400,300' rel='stylesheet' type='text/css'><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="/Assets/css/style.css">
    </head>
    <body class="container">
      <form name="review" action="/home/add" method="post" enctype="multipart/form-data">
        <ul class="flex-outer">
          <li>
            <label for="full_name">ФИО*</label>
            <input type="text" id="full_name" name="full_name" placeholder="Введите ваше ФИО" required>
          </li>
          <li>
            <label for="subject">Тематика*</label>
            <!--input type="text" id="last-name" placeholder="Enter your last name here"-->
            <select id="subject" name="subject" required>
              <option value="" disabled selected>Выберите тематику</option>
              <?php foreach ($allSubjects as $subject): ?>
                  <option value="<?= $subject['id'] ?>"><?= $subject['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </li>
          <li>
            <label for="description">Отзыв*</label>
            <textarea rows="6" id="description" name="description" placeholder="Напишите ваш отзыв" required></textarea>
          </li>
          <li>
            <label for="image">Изображение</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png">
          </li>
            <li>
                <label for="captcha">
                    <img id="reload" src="/index/captcha">
                </label>
                <input type="text" id="captcha" name="captcha" placeholder="Введите код" required>
            </li>
          <li>
            <button type="submit" id="submit">Добавить</button>
          </li>
        </ul>
      </form>
        <?php if ($totalReviews > 0): ?>
        <a href="?<?= $linkSortDate; ?>">Сортировка по дате</a>
        <span>Записей на страницу</span>
        <a href="?<?= $linkSortLimit7 ?>">7</a>
        <a href="?<?= $linkSortLimit14 ?>">14</a>
        <a href="?<?= $linkSortLimit21 ?>">21</a>
        <a href="?<?= $linkSortLimit70 ?>">70</a>
        <?php foreach ($allReviews as $review): ?>
        <table>
            <tr>
                <td>ФИО</td>
                <td><?= $review['full_name'] ?></td>
            </tr>
            <tr>
                <td>Тема</td>
                <td><?= $review['name'] ?></td>
            </tr>
            <tr>
                <td>Отзыв</td>
                <td><?= $review['description'] ?></td>
            </tr>
            <tr>
                <td>Изображение</td>
                <td>
                    <?php if ($review['image']) :?>
                        <img src="/Uploads/images/<?= $review['image'] ?>" height="200">
                    <?php else: ?>
                        <?= 'Изображение отсутствует'; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Likes</td>
                <td><span><?= $review['likes']; ?></span>| <span class="like" data-review-id="<?= $review['review_id']; ?>"><a href="#">Поставить лайк</a></span></td>
            </tr>
            <tr>
                <td>Дата добавления</td>
                <td><?= $review['date']; ?></td>
            </tr>
        </table>
        <hr>
        <?php endforeach; ?>
        <?= $pagination; ?>
            <p>Аналитика: <?= $analytics; ?></p>
            <p>Демонстрационные данные</p>
            <p>Клиенты нас любят: <?= $likes; ?></p>
            <p>Нам надо совершенствоваться: <?= $improves ?></p>
            <p>Пора меняться: <?= $timeToChange; ?></p>
            <p>Надо сжечь это место: <?= $complaints; ?></p>
        <?php else: ?>
            <p>Отзывов нет</p>
        <?php endif; ?>
    <script src="/Assets/js/main.js"></script>
    </body>
</html>




