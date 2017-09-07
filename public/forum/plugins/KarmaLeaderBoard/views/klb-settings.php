<?php if (!defined('APPLICATION'))exit();
echo $this->Form->Open();
echo $this->Form->Errors();
?>


<h1><?php echo Gdn::Translate('Karma Leader Board'); ?></h1>

<div class="Info"><?php echo Gdn::Translate('Karma Leader Board'); ?></div>

<table class="AltRows">
    <thead>
        <tr>
            <th class="Alt"><?php echo Gdn::Translate('Description'); ?></th>
            <th><?php echo Gdn::Translate('Option'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="Alt">
             <?php echo Gdn::Translate('Enter the Users to list in Karma Leader Board Panel'); ?>
            </td>
  
            <td>

                <?php
                $Options = array('1' => '1' , '2' => '2' ,'3' => '3' ,'4' => '4' ,'5' => '5' ,'6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '15' => '15', '20' => '20');
                $Fields = array('TextField' => 'Code', 'ValueField' => 'Code');
                echo $this->Form->DropDown('Plugins.KarmaLeaderBoard.Limit', $Options, $Fields);
                ?>

            </td>
          
        </tr>
    
</table>
<br />
<br />
<?php
echo $this->Form->Close('Save');?>

<br />
<br />

<table>
<tr><td>
<h3><strong>Please consider making a small contribution by clicking on the donate button </strong></h3>
</td><td>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="R78ZA8B7MTFYW">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</td></tr>

</table>



