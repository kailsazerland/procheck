<?php
$this->Paginator->options(array('class' => 'ajax-link','url' => $this->passedArgs));
?>
<script>
    if(!window.jQuery){
        document.write('<scr'+'ipt type="text/javascript" src="/lib/jquery/jquery.min.js"></scr'+'ipt>');
        document.write('<scr'+'ipt type="text/javascript" src="/lib/jquery-ui/jquery-ui.min.js"></scr'+'ipt>');
    }

</script>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <ol class="breadcrumb">
            <li class="hidden-xs"><a href="#">К началу</a></li>
            <li><a href="#">Заявки</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="navbar-form">
        <div class="navbar-btn navbar-left">
            <?php echo $this->Html->link('Добавить','add',array('class' => 'btn btn-success ajax-link'));?>
        </div>
        <?php echo $this->element('search',array('search_view' => $search, 'class' => 'navbar-text')); ?>
    </div>
</div>
<div class="table-responsive">
<table class='table table-hover table-striped table-bordered table-condensed' id="datatable">
    <thead>
    <tr>
        <th><?php echo $this->Admin->PaginatorSort('date','Дата') ?></th>
        <th><?php echo $this->Admin->PaginatorSort('due_date','Срок оплаты') ?></th>
        <th><?php echo $this->Admin->PaginatorSort('money','Сумма') ?></th>
        <th>Организация</th>
        <th>Контрагент</th>
        <th>Способ оплаты</th>
        <th><?php echo $this->Admin->PaginatorSort('article_id','Статья') ?></th>
        <th>Классификация</th>
        <th>Источник</th>
        <th>Примечание</th>
        <th>Файл</th>
        <th><?php echo $this->Admin->PaginatorSort('User.username','Автор') ?></th>
        <?if(!$paid){?><th><?php echo $this->Admin->PaginatorSort('is_confrim','Утверждение') ?></th><?}?>
        <th>Управление</th>

    </tr>
    </thead>
    <?php
//    echo "<pre>";
//    print_r($aunps);
//    echo "</pre>";
    foreach ($aunps AS $value)
    {
        $fil = json_decode($value['Aunpaid']['file_id'],true);
        $outFile = '';
        if(isset($fil) && is_array($fil)) {
            foreach ($fil as $key => $f) {
                $outFile .= '<a href="/files/' . $f["url"] . '" target="_blank" title="'.$f["name"].'">' . $f["name"] . '</a><br />';
            }
        }
//        echo "<pre>";print_r($action_menu);echo "</pre>";
        if(!$paid) {
            if($value['Aunpaid']['utver'] == 0){
                unset($action_menu[4]);
            }else{
                $action_menu[4] = array('name' => 'Платеж','icon' => 'fa fa-credit-card','controller' => 'pays','action' => 'add','class' => 'btn-primary');
            }
            echo $this->Html->TableCells(array(
            $this->Time->format('d.m.Y', $value['Aunpaid']['date'])
            
            , $this->Time->format('d.m.Y', $value['Aunpaid']['due_date'])
            , number_format($value['Aunpaid']['money'],2, ",", "")
            , $value['Orgs']['name']
            , $value['Contragent']['name']
            , $value['PayType']['name']
            , $value['Article']['name']
            , $value['Classification']['name']
            , $value['Source']['name']
            , '<div class="hide_desc">'.$value['Aunpaid']['note']."</div>"
            , '<div class="hide_file">'.$outFile."</div>"
            , $value['User']['username']
            , ($value['Aunpaid']['utver'] != 0) ? '<div class="text-center"><i class="fa fa-check fa-3 img-circle text-success"><i><div>' : ''
            , array('<a href="javascript:void(0);" class="elOpen" ids="'.$value['Aunpaid']['id'].'">Развернуть</a><div class="btn-group popBlock" style="display: none" id="elem'.$value['Aunpaid']['id'].'">' . $this->ActionPanel->DrawPanel($action_menu, $value['Aunpaid']['id']) . '</div>', array('class' => 'listShow relative'))
            ));
        }else{
            echo $this->Html->TableCells(array(
                $this->Time->format('d.m.Y', $value['Aunpaid']['date'])

            , $this->Time->format('d.m.Y', $value['Aunpaid']['due_date'])
            , number_format($value['Aunpaid']['money'],2, ",", "")
            , $value['Orgs']['name']
            , $value['Contragent']['name']
            , $value['PayType']['name']
            , $value['Article']['name']
            , $value['Classification']['name']
            , $value['Source']['name']
            , '<div class="hide_desc">'.$value['Aunpaid']['note']."</div>"
            , '<div class="hide_file">'.$outFile."</div>"
            , $value['User']['username']
            , array('<a href="javascript:void(0);" class="elOpen" ids="'.$value['Aunpaid']['id'].'">Развернуть</a><div class="btn-group popBlock" style="display: none" id="elem'.$value['Aunpaid']['id'].'">' . $this->ActionPanel->DrawPanel($action_menu, $value['Aunpaid']['id']) . '</div>', array('class' => 'listShow relative'))
            ));
        }
    }
    ?>
</table>

<?php
if($this->Paginator->numbers()):
    ?>
    <?php
    echo '<ul class="pagination pagination-sm">';
    echo $this->Paginator->prev('<span class="glyphicon glyphicon-step-backward"></span>', array('escape' => false, 'tag' => 'li'), null, array('escape' => false, 'tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
    echo $this->Paginator->numbers(array(
        'currentClass' => 'active',
        'currentTag' => 'a',
        'tag' => 'li',
        'separator' => ''
    ));
    echo $this->Paginator->next('<span class="glyphicon glyphicon-step-forward"></span>', array('escape' => false, 'tag' => 'li', 'currentClass' => 'disabled'), null, array('escape' => false, 'tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
    echo '  </ul>';
    ?>
<?php endif; ?>
<script>
    var outerWidth = $('.table-responsive').outerWidth();
    $("#slider").width($('.table-responsive').width()).slider({
        max: outerWidth / 2,
        slide: function (event, ui) {
            $('.table-responsive').scrollLeft(ui.value);
        }
    });
    $('.listShow > a.elOpen').on('click', function () {
        var ids = $(this).attr('ids');
        if ($('#elem'+ids).is(":hidden")) {
            $('#elem'+ids).slideDown("fast");
        } else {
            $('#elem'+ids).hide();
        }
        if($(this).text()=='Развернуть'){
            $(this).text('Скрыть');
        }
        else{
            $(this).text('Развернуть');
        }
    });
</script>
</div>