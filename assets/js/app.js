/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
require('bootstrap');


let parent;
let info = $('#info');
$("#form").hide();
$("#got").click(function(){
    $("#form").hide();
    parent = $(this);
    info.empty();
    $.getJSON("api/got-invitation", function(result){
        if(result.length !== 0) {
            $.each(result, function (i, field) {
                let row = '<div class="p-5 bg-light"><h4>' + field.title + '</h4><p>' + field.description + '</p><span>From: ' + field.sender.username + '</span>'+
                    ' | <span>To: ' + field.invited.username + '</span><p>Status: '+field.accepted+'</p>';
                if(field.accepted === 'none'){
                    row += '<button id="accept" data-id="' + field.id + '" class="btn btn-warning mr-5">Accept</button>'+
                        '<button id="reject" data-id="' + field.id + '" class="btn bg-danger">Reject</button>';
                }
                row += '</div>';
                info.append(row);
            });
            update(parent);
        }else{
            info.append('<h4>List of invitations is empty</h4>');
        }
    });
});

$("#sent").click(function(){
    $("#form").hide();
    parent = $(this);
    info.empty();
    $.getJSON("api/sent-invitation", function(result){
        if(result.length !== 0) {
            $.each(result, function (i, field) {
                let row = '<div class="p-5 bg-light"><h4>' + field.title + '</h4><p>' + field.description + '</p><span>From: ' + field.sender.username + '</span>'+
                    ' | <span>To: ' + field.invited.username + '</span><p>Status: '+field.accepted+'</p>';
                if(field.accepted === 'none'){
                    row += '<button id="cancel" data-id="' + field.id + '" class="btn bg-danger">Cancel</button>';
                }
                row += '</div>';
                info.append(row);
            });
            update(parent);
        }else{
            info.append('<h4>List of invitations is empty</h4>');
        }
    });
});

$("#new").click(function () {
    info.empty();
    $("#form").show();
});

function action(parent, button){
    let id = button.attr('data-id');
    let action = button.text();
    $.post("api/invitation/update", { id: id, action: action}, function (result) {
    })
    .done(function () {
        parent.trigger( "click" );
    });
}

function update(parent) {
    let button;
    $("#cancel").click(function () {
        button = $(this);
        action(parent,button);
    });
    $("#accept").click(function () {
        button = $(this);
        action(parent,button);
    });
    $("#reject").click(function () {
        button = $(this);
        action(parent,button);
    });
}

$("#form_save").click(function (event) {
    event.preventDefault();
    let title = $("#form_title").val();
    let description = $("#form_description").val();
    let invite = $("#form_invited").val();
    $.post("api/invitation/add", { title: title, description: description, invite: invite}, function (result) {
    })
    .done(function (response) {
        alert(JSON.stringify(response.status));
        $("#sent").trigger("click");
    })
        .fail(function (response) {
            alert("Error: " + JSON.stringify(response.responseText));
        })

});