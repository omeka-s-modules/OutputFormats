$(function() {

$('button.output-formats-button').on('click', function(e) {
    const selector = $(this).closest('div.output-formats-selector');
    const select = selector.find('select.output-formats-select');
    const url = new URL(selector.data('url'));
    const query = selector.data('query');
    query.format = select.val();
    if ('jsonld' === select.val()) {
        query.pretty_print = 1;
    }
    url.search = new URLSearchParams(query).toString();
    window.open(url.toString());
});

});
