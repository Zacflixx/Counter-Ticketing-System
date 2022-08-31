<?php
session_start();
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucwords(str_replace('_', ' ', $page)) ?> | Counter Queuing System</title>
    <link rel="stylesheet" href="./Font-Awesome-master/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./select2/css/select2.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./DataTables/datatables.min.css">
    <script src="./DataTables/datatables.min.js"></script>
    <script src="./Font-Awesome-master/js/all.min.js"></script>
    <script src="./select2/js/select2.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }

        html,
        body {
            height: 100%;
            width: 100%;
        }

        .form-control.border-0 {
            transition: border .2s cubic-bezier(0.4, 0, 1, 1);
        }

        .form-control.border-0:focus {
            box-shadow: unset !important;
            border-color: var(--bs-info) !important;
        }
    </style>


</head>

<body>
    <main>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient" id="topNavBar">
            <div class="container">
                <a class="navbar-brand" href="./">
                    Counter - Customer Screen
                </a>
            </div>
        </nav>
        <div class="container py-3" id="page-container">
            <?php
            if (isset($_SESSION['flashdata'])) :
            ?>
                <div class="dynamic_alert alert alert-<?php echo $_SESSION['flashdata']['type'] ?>">
                    <div class="float-end"><a href="javascript:void(0)" class="text-dark text-decoration-none" onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a></div>
                    <?php echo $_SESSION['flashdata']['msg'] ?>
                </div>
                <?php unset($_SESSION['flashdata']) ?>
            <?php endif; ?>
            <div class="container-fluid py-5">
                <div style="display: flex; flex:100%" style="height: 20rem !important;">
                    <div style=" flex:50%">
                        <div class="row justify-content-center">
                            <div class="col-md-7">

                                <div class="card rouded-0 shadow">
                                    <div class="card-header rounded-0">
                                        <div class="h5 card-title">Get your Queue Number Here.</div>
                                    </div>
                                    <div class="card-body rounded-0">
                                        <form action="" id="queue-form">
                                            <div class="form-group">
                                                <label for="customer_name" class="control-label text-info">Enter your Name</label>
                                                <input type="text" id="customer_name" name="customer_name" autofocus autocomplete="off" class="form-control form-control-lg rounded-0 border-0 border-bottom" required>
                                            </div>
                                            <div class="form-group text-center my-2">
                                                <button class="btn-primary btn-lg btn col-sm-4 rounded-0" style="width: 9rem;" type='submit'>Get Queue</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div style=" flex:50% ;height: 20rem !important;">

                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h3 class="card-title">Counter List</h3>

                            </div>
                            <div class="card-body">
                                <table class="table table-hover table-striped table-bordered">
                                    <colgroup>
                                        <col width="10%">
                                        <col width="30%">
                                        <col width="30%">
                                        <col width="30%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="text-center p-0">#</th>
                                            <th class="text-center p-0">Name</th>
                                            <th class="text-center p-0">Log Status</th>
                                            <th class="text-center p-0">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM `cashier_list`  order by `name` asc";
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
                <div class="container-fluid">
                            <center><button class="btn btn-lg btn-primary " id="start" type="button">Start Live Queue Monitor</button>

                            </center>
                            <div class="border-dark border-3 border shadow d-none" id="monitor-holder" style="height: 100% ;">
                                <div class="row my-0 mx-0">
                                    <div class="col-md-5 d-flex flex-column justify-content-center align-items-center border-end border-dark" id="serving-field" style="height: 19rem;">

                                        <div class="card col-sm-12 shadow h-100">
                                            <div class="card-header">
                                                <h5 class="card-title text-center">Now Serving</h5>
                                            </div>
                                            <div class="card-body h-100">
                                                <div id="serving-list" class="list-group overflow-auto">
                                                    <?php
                                                    $cashier = $conn->query("SELECT * FROM `cashier_list` order by `name` asc");
                                                    while ($row = $cashier->fetchArray()) :
                                                    ?>
                                                        <div class="list-group-item" data-id="<?php echo $row['cashier_id'] ?>" style="display:none">
                                                            <div class="fs-5 fw-2 cashier-name border-bottom border-info"><?php echo $row['name'] ?></div>
                                                            <div class="ps-4"><span class="serve-queue fs-4 fw-bold">1001 - John Smith</span></div>
                                                        </div>
                                                    <?php endwhile; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 d-flex flex-column justify-content-center align-items-center bg-dark bg-gradient text-light" id="action-field" style="height: 20rem ;">

                                        <div id="datetimefield" class="w-100  col-auto">
                                            <div class="fs-1 text-center time fw-bold"></div>
                                            <div class="fs-5 text-center date fw-bold"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>


    </main>
    <div class="modal fade" id="uni_modal" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uni_modal_secondary" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal_secondary form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-primary btn-sm rounded-0" id='confirm' onclick="">Continue</button>
                    <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="uni_modal" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uni_modal_secondary" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal_secondary form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-primary btn-sm rounded-0" id='confirm' onclick="">Continue</button>
                    <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
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
        websocket.onclose = function(event) {
            console.log('socket has been closed!')
            var websocket = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:2306/queuing/php-sockets.php");
        };
      
        function speak($text = "") {
            if ($text == '')
                return false;
            tts.text = $text;
            notif_audio.setAttribute('muted', false)
            notif_audio.play()
            setTimeout(() => {
                window.speechSynthesis.speak(tts);
                tts.onend = () => {
                    vid_loop.play()
                }
            }, 500);
        }

        function time_loop() {
            var hour, min, ampm, mo, d, yr, s;
            let mos = ['', 'January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
            var datetime = new Date();
            hour = datetime.getHours()
            min = datetime.getMinutes()
            s = datetime.getSeconds()
            ampm = hour >= 12 ? "PM" : "AM";
            mo = mos[datetime.getMonth()]
            d = datetime.getDay()
            yr = datetime.getFullYear()
            hour = hour >= 12 ? hour - 12 : hour;
            hour = String(hour).padStart(2, 0)
            min = String(min).padStart(2, 0)
            s = String(s).padStart(2, 0)
            $('.time').text(hour + ":" + min + ":" + s + " " + ampm)
            $('.date').text(mo + " " + d + ", " + yr)


        }

        function _resize_elements() {
            var window_height = $(window).height()
            var nav_height = $('nav').height()
            var container_height = window_height - nav_height
            $('#serving-field,#action-field').height(container_height - 50)
            $('#serving-list').height($('#serving-list').parent().height() - 30)
        }

        function new_queue($cashier_id, $qid) {
            $.ajax({
                url: './Actions.php?a=get_queue',
                method: 'POST',
                data: {
                    cashier_id: $cashier_id,
                    qid: $qid
                },
                dataType: 'JSON',
                error: err => {
                    console.log(err)
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        var item = $('#serving-list').find('.list-group-item[data-id="' + $cashier_id + '"]')
                        var cashier = item.find('.cashier-name').text()
                        var nitem = item.clone()
                        nitem.find('.serve-queue').text(resp.queue + " - " + resp.name)
                        item.remove()
                        $('#serving-list').prepend(nitem)
                        if (resp.queue == '') {
                            nitem.hide('slow')
                        } else {
                            nitem.show('slow')
                            speak("Queue Number " + (Math.abs(resp.queue)) + resp.name + ", Please proceed to " + cashier)
                        }
                    }
                }
            })
        }
        $(function() {
            setInterval(() => {
                time_loop()
            }, 1000);
            $('#start').click(function() {
                $(this).hide()
                $('#monitor-holder').removeClass('d-none')
                _resize_elements()
                vid_loop.play()
            })
            $(window).resize(function() {
                _resize_elements()
            })

            websocket.onmessage = function(event) {
                var Data = JSON.parse(event.data);
                if (!!Data.type && typeof Data.type != undefined && typeof Data.type != null) {
                    if (Data.type == 'queue') {
                        new_queue(Data.cashier_id, Data.qid)
                    }
                    if (Data.type == 'test') {
                        speak("This is a sample notification.")
                    }
                }
            }
        })
    </script>
    <script>
        $(function() {
            $('#queue-form').submit(function(e) {
                e.preventDefault()
                var _this = $(this)
                _this.find('.pop-msg').remove()
                var el = $('<div>')
                el.addClass('alert pop-msg')
                el.hide()
                _this.find('button[type="submit"]').attr('disabled', true)
                $.ajax({
                    url: './Actions.php?a=save_queue',
                    method: 'POST',
                    data: _this.serialize(),
                    dataType: 'JSON',
                    error: err => {
                        console.log(err)
                        el.addClass("alert-danger")
                        el.text("An error occured while saving data.")
                        _this.find('button[type="submit"]').attr('disabled', false)
                        _this.prepend(el)
                        el.show('slow')
                    },
                    success: function(resp) {
                        if (resp.status == 'success') {
                            uni_modal("Your Queue", "get_queue.php?success=true&id=" + resp.id)
                            $('#uni_modal').on('hide.bs.modal', function(e) {
                                location.reload()
                            })
                        } else if (resp.status = 'failed' && !!resp.msg) {
                            el.addClass('alert-' + resp.status)
                            el.text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                        } else {
                            el.addClass('alert-' + resp.status)
                            el.text("An Error occured.")
                            _this.prepend(el)
                            el.show('slow')
                        }
                        _this.find('button[type="submit"]').attr('disabled', false)
                    }
                })
            })
        })
    </script>
</body>

</html>