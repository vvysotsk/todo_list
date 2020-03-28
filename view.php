<?php session_start(); ?>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            .head {
                text-align: center;
                font-weight: bold;
                background: #F7F7F7;
            }
            .task_panel > .row div {
                border: 1px solid black;
            }
            .container{
                
            }
            .sign_in {
                
            }
            .sort:hover{
                cursor: pointer;
            }
            #addTask input{
                margin: 5px;
            }
        </style>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="jquery-3.4.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.changeTaskBtn').click(function(){
                    let task_id = $(this).find('input[name="task_id"]').val();
                    $.ajax({
                        type: 'post',
                        url: 'controller.php',
                        dataType: 'JSON',
                        data: {
                            task_id: task_id,
                            action: 'getTaskInfo'
                        },
                        success: function(task){
                            let modal = $('#changeTaskForm');
                            $(modal).find('input[name="task_id"]').val(task_id);
                            $(modal).find('input[name="username"]').val(task.username);
                            $(modal).find('input[name="email"]').val(task.email);
                            $(modal).find('input[name="description"]').val(task.description);
                            if (task.status == '1'){
                                $(modal).find('input[name="status"]').prop("checked", true);
                            } else {
                                $(modal).find('input[name="status"]').prop("checked", false);
                            }
                        }
                    });
                });
                
                $('.sort').click(function(){
                    let direction = '';
                    let icon = $(this).find('i');
                    if ($(icon).hasClass('fa-arrow-down')){
                        $(icon).removeClass('fa-arrow-down');
                        $(icon).addClass('fa-arrow-up');
                        direction = 'ASC';
                    } else if($(icon).hasClass('fa-arrow-up')){
                        $(icon).removeClass('fa-arrow-up');
                        $(icon).addClass('fa-arrow-down');
                        direction = 'DESC';
                    }
                   let order_by = $(this).data('order_by');
                   
                   $.ajax({
                    type: "POST",
                    url: 'controller.php',
                    data: {
                        order_by : order_by,
                        direction : direction,
                        action: 'setOrder'
                    },
                    success: function(){location.reload();}
                  });
                });
            });
        </script>
    </head>
    <body>
        <div class="content">
            <?php if (!isset($_SESSION['user'])): ?>
            <div class="sign_in container" style="margin-bottom: 40px">
                <div class="row justify-content-center align-items-center">
                    <div class="col-4">
                        <div class="card">
                            <div class="card-body">
                                <form action="controller.php" autocomplete="off" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="login" placeholder="login" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="passwd" placeholder="password" required>
                                    </div>
                                    <input type='hidden' name='action' value='login'/> 
                                    <button type="submit" class="btn btn-primary">login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="log_out container" style="margin-bottom: 10px">
                <form action="controller.php" autocomplete="off" method="post">
                    <button type="submit" class="btn btn-default btn-sm" name='action' value='logout'>
                        <input type='hidden' name='action' value='logout'/>
                        <span class="glyphicon glyphicon-log-out"></span> Log out
                    </button>
                </form>
            </div>
            <?php endif; ?>
<!--add modal form call-->
            <div class="text-center" style="margin-bottom: 10px">
                <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#addTaskForm">Add task</a>
            </div>
<!--end-->        
            
            <div class="task_panel container">
                <div class="row head_row">
                    <div class="col-sm-2 head sort" data-order_by="username">
                        <span>имя пользователя<i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                    </div>
                    <div class="col-sm-3 head sort" data-order_by="email">
                        <span>email<i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                    </div>
                    <div class="col-sm-5 head">
                        <span>текст задачи</span>
                    </div>
                    <div class="col-sm-2 head sort" data-order_by="status">
                        <span>статус<i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                    </div>
                </div>
                <?php for ($num = $start; $num < $start+3 && $tasks[$num]; $num++): ?>
                    <div class="row">
                        <div class="col-sm-2" style="height: 13vh">
                            <span class="data"><?php echo htmlspecialchars($tasks[$num]['username']); ?></span>
                        </div>
                        <div class="col-sm-3" style="height: 13vh">
                            <span class="data"><?php echo htmlspecialchars($tasks[$num]['email']); ?></span>
                        </div>
                        <div class="col-sm-5" style="height: 13vh">
                            <span class="data"><?php echo htmlspecialchars($tasks[$num]['description']); ?></span>
                        </div>
                        <div class="col-sm-2" style="height: 13vh">
                            <?php if($tasks[$num]['status']): ?>
                                <span class="data">Task is completed</span>
                            <?php else: ?>
                                <span class="data">In proccess</span>
                            <?php endif;
                            if ($tasks[$num]['changed']):?>
                                <span class="data">, Changed by admin</span>
                            <?php endif; ?>
                        </div>
                        <?php if($_SESSION['user'] == 'admin'): ?>
<!--change modal form call-->
                            <span class="data">
                                <div class="text-center" style="margin-bottom: 10px; border:0px;">
                                <a href="" class="changeTaskBtn btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#changeTaskForm">Change task
                                <input type='hidden' name='task_id' value='<?php echo $tasks[$num]['id'] ?>'/></a>
                                </div>
                            </span>
<!--end--> 
                        <?php endif; ?>
                    </div>
                <?php endfor; 
                if ($page != 1){
                    $pervpage = '<a href= ./index.php?page=1><<</a>'
                            . '<a href= ./index.php?page=' . ($page - 1) . '><</a> ';
                }
                if ($page != $total) {
                    $nextpage = ' <a href= ./index.php?page=' . ($page + 1) . '>></a>'
                            . '<a href= ./index.php?page=' . $total . '>>></a>';
                }
                if ($page - 2 > 0)
                    $page2left = ' <a href= ./index.php?page=' . ($page - 2) . '>' . ($page - 2) . '</a> | ';
                if ($page - 1 > 0)
                    $page1left = '<a href= ./index.php?page=' . ($page - 1) . '>' . ($page - 1) . '</a> | ';
                if ($page + 2 <= $total)
                    $page2right = ' | <a href= ./index.php?page=' . ($page + 2) . '>' . ($page + 2) . '</a>';
                if ($page + 1 <= $total)
                    $page1right = ' | <a href= ./index.php?page=' . ($page + 1) . '>' . ($page + 1) . '</a>';

                echo $pervpage . $page2left . $page1left . '<b>' . $page . '</b>' . $page1right . $page2right . $nextpage;
                ?>
            </div>
        </div>
<!--change modal form-->
<div class="modal fade" id="changeTaskForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Change task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="controller.php" autocomplete="off" method="post">
                <div class="modal-body mx-3">
                    <input type='hidden' name='action' value='changeTask'/>
                    <input type='hidden' name='task_id' value=''/>
                    <div class="md-form mb-5">
                        <input name="username" class="form-control" required placeholder="username">
                    </div>

                    <div class="md-form mb-5">
                        <input name="email" type="email" class="form-control validate" required placeholder="email">
                    </div>

                    <div class="md-form mb-5">
                        <input name="description" class="form-control" required placeholder="description">
                    </div>
                    <div class="md-form mb-5">
                        <label class="form-control" style="text-align: center;">Status:
                        <input id="checkbox" name="status" type="checkbox"></label>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-default">Change</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!--add modal form-->
<div class="modal fade" id="addTaskForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Add new task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addTask" action="controller.php" autocomplete="off" method="post">
                <div class="modal-body mx-3">
                    <input type='hidden' name='action' value='addTask'/>
                    <div class="md-form mb-5">
                        <input name="username" class="form-control" required placeholder="username">
                    </div>

                    <div class="md-form mb-5">
                        <input name="email" type="email" id="defaultForm-email" class="form-control validate" required placeholder="email">
                    </div>

                    <div class="md-form mb-5">
                        <input name="description" class="form-control" required placeholder="description">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-default">Add</button>
                </div>
            </form>

        </div>
    </div>
</div>
    </body>
</html>