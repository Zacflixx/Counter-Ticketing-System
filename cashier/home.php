

<div class="container">
    <div class="row">
        <div class="col-md-6 d-fdlex flex-column justify-content-center align-items-center" id="serving-field" style="width: 50%;">

            <div class="card col-sm-8 shadow">
                <div class="card-header">
                    <h5 class="card-title text-center">Now Serving</h5>
                </div>
                <div class="card-body">
                    <div class="fs-1  my-2 fw-bold text-center"><span id="queue">----</span></div>
                </div>
                <div class="card-footer">
                <div class="col">
                    <button id="next_queue" class="btn btn-flat btn-primary rounded-0 btn-lg"><i class="fa fa-forward"></i> Call Next</button>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-ssflex   justify-content-center align-items-center" id="action-field" style="width: 50%;">
        <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Counter Control</h3>
            <?php echo $_SESSION['name'] ?>
        </div>
        
        <div class="card-body">
            <table class="table table-hover table-striped table-bordered">
                <colgroup>
                    <col width="5%">
                    <col width="30%">
                    <col width="25%">
                    <col width="25%">
                    <col width="15%">
                </colgroup>
                <thead>
                    <tr> 
                        <th class="text-center p-0">#</th>
                        <th class="text-center p-0">Name</th>
                        <th class="text-center p-0">Log Status</th>
                        <th class="text-center p-0">Status</th>
                        <th class="text-center p-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM `cashier_list` where `log_status` = 1  order by `name` asc";
                    $qry = $conn->query($sql);
                    $i = 1;
                    while ($row = $qry->fetchArray()) :
                    ?>
                        <tr>
                            <td class="text-center p-0"><?php echo $i++; ?></td>
                            <td class="py-0 px-1"><?php echo $row['name'] ?></td>
                            <td class="py-0 px-1 text-center">
                                <?php
                                if ($row['log_status'] == 1) {
                                    echo  '<span class="py-1 px-3 badge rounded-pill bg-success"><small>In-Use</small></span>';
                                } else {
                                    echo  '<span class="py-1 px-3 badge rounded-pill bg-danger"><small>Not In-Use</small></span>';
                                }
                                ?>
                            </td>
                            <td class="py-0 px-1 text-center">
                                <?php
                                if ($row['status'] == 1) {
                                    echo  '<span class="py-1 px-3 badge rounded-pill bg-success"><small>Active</small></span>';
                                } else {
                                    echo  '<span class="py-1 px-3 badge rounded-pill bg-danger"><small>In-Active</small></span>';
                                }
                                ?>
                            </td>
                            <th class="text-center py-0 px-1">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                                    <li><a class="dropdown-item edit_data" data-id='<?php echo $row['cashier_id'] ?>' href="javascript:void(0)">Edit</a></li>
                                    </button>
                                     
                                </div>
                            </th>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (!$qry->fetchArray()) : ?>
                        <tr>
                            <th class="text-center p-0" colspan="5">No data display.</th>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </div>
   
    <script>
        $(function() {
            $('#create_new').click(function() {
                uni_modal('Add New Cashier', "manage_cashier.php")
            })
            $('.edit_data').click(function() {
                uni_modal('Edit Cashier Details', "manage_cashier.php?id=" + $(this).attr('data-id'))
            })
            $('.delete_data').click(function() {
                _conf("Are you sure to delete <b>" + $(this).attr('data-name') + "</b> from list?", 'delete_data', [$(this).attr('data-id')])
            })
        })

        function delete_data($id) {
            $('#confirm_modal button').attr('disabled', true)
            $.ajax({
                url: './Actions.php?a=delete_cashier',
                method: 'POST',
                data: {
                    id: $id
                },
                dataType: 'JSON',
                error: err => {
                    console.log(err)
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled', false)
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        location.reload()
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>')
                        el.addClass('alert alert-danger pop-msg')
                        el.text(resp.msg)
                        el.hide()
                        $('#confirm_modal .modal-body').prepend(el)
                        el.show('slow')
                    } else {
                        alert("An error occurred.")
                    }
                    $('#confirm_modal button').attr('disabled', false)

                }
            })
        }
    </script>

<script>
    var websocket = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:2306/queuing/php-sockets.php"); 
    websocket.onopen = function(event) { 
      console.log('socket is open!')
		}
    websocket.onclose = function(event){
      console.log('socket has been closed!')
    var websocket = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:2306/queuing/php-sockets.php"); 
    };
    let tts = new SpeechSynthesisUtterance();
    tts.lang = "en"; 
    tts.voice = window.speechSynthesis.getVoices()[0] ; 
    let notif_audio = new Audio("./audio/ascend.mp3")
    let vid_loop = $('#loop-vid')[0]
    tts.onstart= ()=>{
        vid_loop.pause()
    }
    notif_audio.setAttribute('muted',true)
    notif_audio.setAttribute('autoplay',true)
    document.querySelector('body').appendChild(notif_audio)
    function speak($text=""){
        if($text == '')
        return false;
        tts.text = $text; 
        notif_audio.setAttribute('muted',false)
        notif_audio.play()
        setTimeout(() => {
            window.speechSynthesis.speak(tts); 
           tts.onend= ()=>{
                vid_loop.play()
            }
        }, 500);
    }
    function time_loop(){
        var hour,min,ampm,mo,d,yr,s;
        let mos = ['','January','Febuary','March','April','May','June','July','August','September','October','November','December']
        var datetime = new Date();
        hour = datetime.getHours()
        min = datetime.getMinutes()
        s = datetime.getSeconds()
        ampm = hour >= 12 ? "PM" : "AM";
        mo = mos[datetime.getMonth()]
        d = datetime.getDay()
        yr = datetime.getFullYear()
        hour = hour >= 12 ? hour - 12 : hour;
        hour = String(hour).padStart(2,0)
        min = String(min).padStart(2,0)
        s = String(s).padStart(2,0)
        $('.time').text(hour+":"+min+":"+s+" "+ampm)
        $('.date').text(mo+" "+d+", "+yr)
            
            
    }
    function _resize_elements(){
        var window_height = $(window).height()
        var nav_height = $('nav').height()
        var container_height = window_height - nav_height
        $('#serving-field,#action-field').height(container_height - 50)
        $('#serving-list').height($('#serving-list').parent().height() - 30)
    }

    function new_queue($cashier_id,$qid){
        $.ajax({
            url:'./Actions.php?a=get_queue',
            method:'POST',
            data:{cashier_id:$cashier_id,qid:$qid},
            dataType:'JSON',
            error:err=>{
                console.log(err)
            },
            success:function(resp){
                if(resp.status =='success'){
                    var item = $('#serving-list').find('.list-group-item[data-id="'+$cashier_id+'"]')
                    var cashier =  item.find('.cashier-name').text()
                    var nitem = item.clone()
                        nitem.find('.serve-queue').text(resp.queue+" - "+resp.name)
                        item.remove()
                        $('#serving-list').prepend(nitem)
                    if(resp.queue == ''){
                        nitem.hide('slow')
                    }else{
                        nitem.show('slow')
                        speak("Queue Number "+(Math.abs(resp.queue))+resp.name+", Please proceed to "+cashier)
                    }
                }
            }
        })
    }
    $(function(){
        setInterval(() => {
            time_loop()
        }, 1000);
        $('#start').click(function(){
            $(this).hide()
            $('#monitor-holder').removeClass('d-none')
            _resize_elements()
            vid_loop.play()
        })
        $(window).resize(function(){
            _resize_elements()
        })

        websocket.onmessage = function(event) {
			var Data = JSON.parse(event.data);
            if(!!Data.type && typeof Data.type != undefined && typeof Data.type != null){
                if(Data.type == 'queue'){
                    new_queue(Data.cashier_id,Data.qid)
                }
                if(Data.type == 'test'){
                    speak("This is a sample notification.")
                }
            }
        }
    })
</script>
</div>
<script>
    var websocket = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:2306/queuing/php-sockets.php"); 
    websocket.onopen = function(event) { 
      console.log('socket is open!')
		}
    websocket.onclose = function(event){
      console.log('socket has been closed!')
    var websocket = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:2306/queuing/php-sockets.php"); 
    };
    var in_queue = {};
    function _resize_elements(){
        var window_height = $(window).height()
        var nav_height = $('#topNavBar').height()
        var container_height = window_height - nav_height
        $('#serving-field,#action-field').height(container_height - 50)
    }
    function get_queue(){
        $.ajax({
            url:'./../Actions.php?a=next_queue',
            dataType:'json',
            error:err=>console.log(err),
            success:function(resp){
                if(resp.status){
                    if(Object.keys(resp.data).length > 0){
                        in_queue = resp.data
                    }else{
                        in_queue = {}
                        alert("No Queue Available")
                    }
                }else{
                    alert('An error occured')
                }
                queue();
            }
        })

    }
    function queue(){
        $('#queue').text(in_queue.queue || "----")
        websocket.send(JSON.stringify({type:'queue',cashier_id:'<?php echo $_SESSION['cashier_id'] ?>',qid:in_queue.queue_id}))
    }
    _resize_elements();
    $(function(){
        $(window).resize(function(){
            _resize_elements()
        })
        $('#next_queue').click(function(){
            get_queue()
        })
      
    })
</script>