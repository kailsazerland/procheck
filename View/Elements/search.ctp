<?php                
    echo $this->Form->create('Search', array('url' => 'search/' . $search_view , 'class' => 'navbar-form navbar-right', 'role' => 'form'));
    echo '<div class="input-group">';
    echo $this->Form->input('Search.text',array('label' => false,'type' => 'search','placeholder' => ((isset($this->request->params['search_text']))?$this->request->params['search_text']:'Найти...'),'div' => false));
    echo '<div class="input-group-btn">' . $this->Form->button($this->Html->tag('span',false,array('class' => 'glyphicon glyphicon-search')),array('escape' => false,'class' => 'btn btn-default ajax-link','div' => false)) . '</div>'; 
    echo '</div>';
    echo $this->Form->end();
?>