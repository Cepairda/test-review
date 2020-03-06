document.forms.review.addEventListener('submit', uploadReview);
document.getElementById('image').addEventListener('change', checkImage);

function uploadReview(event)
{
    let review = document.forms.review;

    let formData = new FormData(review);

    let request = new XMLHttpRequest();

    request.open('POST', '/index/add', true);
    request.responseType = 'json';
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    request.onload = function (event) {
        if (request.status == 200) {
            if (request.response.code == 1) {
                review.reset();
            } else if (request.response.code == 2) {
                document.getElementById('reload').src = '/index/captcha';
            }

            alert(request.response.message);
        } else {
            //Место для ошибок
        }

    };

    request.send(formData);

    event.preventDefault();
}

function checkImage() {
    let imageType = this.files.length > 0 ? this.files[0].type : false;

    if (imageType || imageType === '') {
        if (imageType != 'image/jpeg' && imageType != 'image/png') {
            this.setCustomValidity('Допустимый формат JPG и PNG');
            image/jpeg
            return;
        }
    }

    this.setCustomValidity('');
}

function likeReview() {
    let subjectId = this.dataset.reviewId;
    let formData = new FormData();

    formData.append('id', subjectId); // добавляем поле
    formData.append('like', 'true'); // добавляем поле



    request = new XMLHttpRequest();

    request.open('POST', '/index/like', true);
    request.responseType = 'json';
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    request.onload = function (event) {
        if (request.status == 200) {
            if (request.response.code == 1) {
                review.reset();
            } else if (request.response.code == 2) {
                document.getElementById('reload').src = '/index/captcha';
            }

            alert(request.response.message);
        } else {
            //Место для ошибок
        }

    };

    request.send(formData);
}

let subjectsLike = document.querySelectorAll('span.like');

Array.from(subjectsLike).forEach(link => {
    link.addEventListener('click', function(event) {
        let subject = this;
        let subjectId = this.dataset.reviewId;
        let formData = new FormData();

        formData.append('id', subjectId); // добавляем поле
        formData.append('like', 'true'); // добавляем поле

        request = new XMLHttpRequest();

        request.open('POST', '/index/like', true);
        request.responseType = 'json';
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        request.onload = function (event) {
            if (request.status == 200) {
                //alert(request.response.message);
                let countLike = subject.previousElementSibling;
                countLike.innerHTML = parseInt(countLike.innerHTML) + 1;
            } else {
                //Место для ошибок
            }

        };

        request.send(formData);

        event.preventDefault();
    });
});