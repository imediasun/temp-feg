<?php if (!defined('APPLICATION')) exit();
?>
    <h1><?php echo $this->Data('Title'); ?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
    <style>
        .adjacent { margin-top: -.2em; }
        
        .Configuration {
            margin: 0 20px 20px;
            background: #f5f5f5;
            float: left;
        }
        .ConfigurationForm {
            padding: 20px;
            float: left;
        }
        .ConfigurationHelp {
            border-left: 1px solid #aaa;
            margin-left: 340px;
            padding: 20px;
        }
        #Content .ConfigurationForm ul { padding: 0; }
        #Content form input.Button { margin: 0; }
        
        .help-image {
            margin-top: 1em;
            max-width: 100%;
            border-radius: .25em;
        }
    </style>
    <div class="Info">
        <p><?php echo T('Brow.si Description', 'Brow.si adds a new layer of interactivity to your forum on mobile devices that helps boost user engagement and traffic.'); ?></p>
        <p class="adjacent"><?php echo T('Brow.si Description2', '<strong>To customize Brow.si and access analytics, please enter your Brow.si Site ID below.</strong>'); ?></p>
    </div>
    <div class="Configuration">
        <div class="ConfigurationForm">
            <ul>
                <li>
                    <?php
                    echo $this->Form->Label('Brow.si Site ID', 'SiteID');
                    echo $this->Form->TextBox('SiteID');
                    ?>
                </li>
            </ul>
            <?php echo $this->Form->Button('Save', array('class' => 'Button SliceSubmit')); ?>
        </div>
        <div class="ConfigurationHelp">
            <strong><?php echo T('Brow.si Help Title', 'How to get a Brow.si Site ID?'); ?></strong>
            <p><?php echo T('Brow.si Help Signup', 'Sign up to <a href="https://brow.si/register" target="_blank" title="Sign up to Brow.si">Brow.si</a> - It\'s FREE!'); ?></p>
            <p><?php echo T('Brow.si Help Install', 'Once you have signed up, copy the "SITE ID" from your <a href="https://brow.si/dashboard" target="_blank" title="Brow.si Dashboard">Dashboard</a> and paste it into the box on this page. Then, click Save and you\'re done :)'); ?></p>
            <?php echo Img('/plugins/Browsi/help-dashboard.png', array('alt' => 'Your Brow.si Site ID on your dashboard', 'class' => 'help-image')); ?>
        </div>
    </div>
<?php
echo $this->Form->Close();
