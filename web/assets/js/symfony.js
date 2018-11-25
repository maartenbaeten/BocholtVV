$(document).ready(function(){
    var rowCount = $('#ranking .row').length;

    $('#add-row').on('click', function(event){
        event.preventDefault();
        rowCount = $('#ranking .row').length;
        rowCount += 1;

        element = $('.team-classification').attr('data-prototype');
        element = element.replace(/__name__/g,  rowCount);

        $('#ranking').append(element);

        fixDelete();

        fixRecursive(rowCount);
    });

    $('#header-row input').on('keyup', function(){
        $(this).next().children().val($(this).val());
    });

    $('.delete-ranking-row').on('click', function(event){
        event.preventDefault();
        $(this).parent().parent().remove();

        processDelete();
    });

    $('.up-ranking-row').on('click', function(event){
        event.preventDefault();
        var id = $(this).parent().parent().attr('data-row-id');
        var element = $(this).parent().parent();

        goUp(element, rowCount, id);

        fixRecursive(rowCount);
    });

    $('.down-ranking-row').on('click', function(event){
        event.preventDefault();
        var id = $(this).parent().parent().attr('data-row-id');
        var element = $(this).parent().parent();

        goDown(element, rowCount, id);

        fixRecursive(rowCount);
    });
});

function goDown(element, rowCount, id){
    if(id != rowCount){
        element.attr('data-row-id', +id + 1);
        var nextId = +id + 1;
        var currentElement = element.html().replace(new RegExp('_'+id+'_', 'g'), '_' + nextId + '_');
        currentElement = currentElement.replace(new RegExp('[['+id+']]', 'g'), nextId + ']');
        var nextElement = element.next().html().replace(new RegExp('_'+nextId+'_', 'g'), '_' + id + '_');
        nextElement = nextElement.replace(new RegExp('[['+nextId+']]', 'g'),id + ']');

        element.html(currentElement);
        element.next().attr('data-row-id', id);
        element.next().html(nextElement);
        element.next().after(element);
    }
}

function goUp(element, rowCount, id){
    if(id != 1){
        element.attr('data-row-id', +id - 1);
        var previousId = +id - 1;
        var currentElement = element.html().replace(new RegExp('_'+id+'_', 'g'), '_' + previousId + '_');
        currentElement = currentElement.replace(new RegExp('[['+id+']]', 'g'), previousId + ']');
        var previousElement = element.prev().html().replace(new RegExp('_'+previousId+'_', 'g'), '_' + id + '_');
        previousElement = previousElement.replace(new RegExp('[['+previousId+']]', 'g'), id + ']');

        element.html(currentElement);
        element.prev().attr('data-row-id', id);
        element.prev().html(previousElement);
        element.prev().before(element);
    }
}

function fixRecursive(rowCount){
    $('.down-ranking-row').on('click', function(event){
        event.preventDefault();
        var id = $(this).parent().parent().attr('data-row-id');
        var element = $(this).parent().parent();

        goDown(element, rowCount, id);

        fixRecursive(rowCount);
    });

    $('.up-ranking-row').on('click', function(event){
        event.preventDefault();
        var id = $(this).parent().parent().attr('data-row-id');
        var element = $(this).parent().parent();

        goUp(element, rowCount, id);

        fixRecursive(rowCount);
    });

    fixDelete();
}

function fixDelete(){
    $('.delete-ranking-row').on('click', function(event){
        event.preventDefault();
        $(this).parent().parent().remove();

        processDelete();
    });
}

function processDelete(){
    var elements = $('#ranking .row');

    for (i = 0; i < elements.length; i++) {
        var elementHtml = $('#ranking .row:eq( ' + i + ' )').html();
        var elementCount = i+1;
        var dataId = $('#ranking .row:eq( ' + i + ' )').attr('data-row-id');

        var currentElement = elementHtml.replace(new RegExp('_'+dataId+'_', 'g'), '_' + elementCount + '_');
        currentElement = currentElement.replace(new RegExp('[['+dataId+']]', 'g'), elementCount + ']');

        $('#ranking .row:eq( ' + i + ' )').attr('data-row-id', elementCount);
        $('#ranking .row:eq( ' + i + ' )').html(currentElement);
    }
}