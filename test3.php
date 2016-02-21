<!DOCTYPE html>
<html>
<head>
<style>
table {
    border-collapse:collapse;
    margin:10px
}
table tr td {
    margin:3px 10px 3px 10px;
}
tr.drag td {
    cursor:move;
}
.high-drop {
    background:#66FF66
}
.temp-place {
    color:#CC0000
}
</style>
<script>
function findClassA( elem , classF){
    var par = elem.parentElement;
    if(par.classList.contains(classF)) { return par;
    }else { return findClassA(par , classF);}
};
function enter_handler(ev){
    ev.target.classList.add("target-drop-area");
    var tab_target = findClassA(ev.target , "drop-area");
    var has_class = tab_target.getElementsByClassName("target-drop-area");
    if(has_class.length == 0){ tab_target.classList.remove("high-drop");
    } else { tab_target.classList.add("high-drop"); }
}
function leave_handler(ev){
    ev.target.classList.remove("target-drop-area");
    var tab_target = findClassA(ev.target , "drop-area");
    var has_class = tab_target.getElementsByClassName("target-drop-area");
    if(has_class.length == 0){ tab_target.classList.remove("high-drop");
    } else { tab_target.classList.add("high-drop"); }
}
function allow_drag(ev){
    ev.preventDefault();
};
function drag_start(ev){
    ev.dataTransfer.setData("text", ev.target.id);
    ev.dataTransfer.dropEffect = "move";
};
function drop_handle(ev){
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    var element = findClassA (ev.target , "drop-area");
    moved_elem = document.getElementById(data);
    element.appendChild(moved_elem);
    moved_elem.classList.add("temp-place");
    element.classList.remove("high-drop");
    ev.target.classList.remove("target-drop-area");   
};
document.addEventListener('DOMContentLoaded', function () {
    var dragel = document.getElementsByClassName("drag");
    for ( i= 0; i < dragel.length ; i++){
        dragel[i].addEventListener('dragstart', drag_start , false);
    }
    var dropel = document.getElementsByClassName("drop-area");
    for ( i= 0 ;i < dropel.length ; i++){
        dropel[i].addEventListener('drop' , drop_handle , false );
        dropel[i].addEventListener('dragover' , allow_drag , false );
        dropel[i].addEventListener('dragenter' , enter_handler , false );
        dropel[i].addEventListener('dragleave' , leave_handler , false );
    }
});

</script>
</head>
<body>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Rank</th>
        </tr>
    </thead>
    <tbody class="drop-area" id="table-one">
        <tr draggable="true" class="drag" id="1">
            <td>John</td>
            <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="2">
             <td>lisa</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="3">
             <td>phill</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="4">
             <td>tammy</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="5">
             <td>oscar</td>
             <td>officer</td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr draggable="true" class="drag" id="6">
            <th>Name</th>
            <th>Rank</th>
        </tr>
    </thead>
    <tbody class="drop-area"id="table-two">
        <tr draggable="true" class="drag" id="7">
            <td>Jacob</td>
            <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="8">
             <td>trudy</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="9">
             <td>oliver</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="10">
             <td>chriss</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="11">
             <td>mandy</td>
             <td>officer</td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr draggable="true" class="drag" id="12">
            <th>Name</th>
            <th>Rank</th>
        </tr>
    </thead>
    <tbody class="drop-area">
        <tr draggable="true" class="drag" id="13">
            <td>Judy</td>
            <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="14">
             <td>adam</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="15">
             <td>jason</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="16">
             <td>kimm</td>
             <td>member</td>
        </tr>
        <tr draggable="true" class="drag" id="17">
             <td>trudy</td>
             <td>officer</td>
        </tr>
    </tbody>
</table>
<button type=button>Save</button>
</body>
</html>