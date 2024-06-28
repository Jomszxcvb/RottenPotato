document.getElementById('search').addEventListener('input', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'search_suggestions.php?query=' + this.value, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var suggestions = JSON.parse(this.responseText);
            var datalist = document.getElementById('suggestions');
            datalist.innerHTML = '';
            suggestions.forEach(function(suggestion) {
                var option = document.createElement('option');
                option.value = suggestion;
                datalist.appendChild(option);
            });
        }
    };
    xhr.send();
});