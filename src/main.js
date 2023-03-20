
function nodeHtmlGenerator (parentID = 0 ,ID = 0 , title = "" , html = '')
{
    return `
        <ul style='list-style-type: none;'>
        <li data-parent-id='${parentID}' data-id='${ID}'> ${title} 
        <input type="text"  data-id='${ID}' placeholder='назва дочірньої ноди' value=""> 
        <button data-parent-id='${parentID}' data-id='${ID}' class='add btn btn-success'>+</button>
        <button data-parent-id='${parentID}'  data-id='${ID}' class='delete-button delete btn btn-danger'> -</button> 
        ${html}
        </li>
        </ul>`;

}
function nodeGenerator  (object)
{
    let html = ""
    for (const item  of  object)
    {
        if(item.childNodes.length > 0)
        {
            html += nodeHtmlGenerator ( item.parent_id, item.id , item.title , nodeGenerator(item.childNodes) )
        }
        else
        {
            html += nodeHtmlGenerator ( item.parent_id, item.id ,item.title )
        }

    }
    return html;
}

function getAllNodes ()
{
    $.ajax({
        url: '/ajax.php?action=get_tree',
        method: 'POST',
        success: function(response) {

            $("#tree").html(nodeGenerator(response) )
        }
    });
}

function deleteNode (node_id)
{
    $.ajax({
        url: '/ajax.php?action=delete_node',
        method: 'POST',
        data: {node_id:node_id},
        success: function(response) {
            getAllNodes ()
        }
    });
}

function createNode (parent_id,title)
{
    $.ajax({
        url: '/ajax.php?action=create_node',
        method: 'POST',
        data:  {parent_id , title },
        success: function(response) {
            getAllNodes ()
        }
    });
}

getAllNodes ()
$(document).ready(function () {

    $("#create-root").click(function () {
     createNode(null, "вузол root")
    });

    $('#tree').on('click', '.add', function () {
        let parentID = $(this).attr('data-id');
        createNode(parentID, $(`input[data-id="${parentID}"]`).val() )
    });
    $(document).on("click",".delete-button" , function() {
        clearInterval();
        timerCountDown ()

        $("#delete_node").val( $(this).attr('data-id'))
        $('#popup').modal('show');
    });

});
let timer
function timerCountDown() {
    let countdown = 30;

    timer = setInterval(function() {
        if (countdown > 0 && $('#popup').hasClass('show')) {
            $('#countdown').text(countdown);
            countdown--;
        } else {
            countdown = 30;
            clearInterval(timer);
            if ($('#popup').hasClass('show')) {
                $('#popup').modal('hide');
            }
        }
    }, 1000);
}


$(document).on("click","#confirm-delete" , function() {
    deleteNode($("#delete_node").val( ) )
    clearInterval();
    $('#popup').modal('hide');

});


$('#cancel-delete').click(function() {
    clearInterval();
    $('#popup').modal('hide');
});
