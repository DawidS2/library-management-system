let isbn = document.querySelector('.isbn');
let url = document.querySelector('form').dataset.url;

isbn.addEventListener('click', function (e) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            isbn: isbn.value
        },
        success: function (data) {
            let specimen = document.querySelector('.specimen');
            specimen.innerHTML = data;
        }
    });
});
