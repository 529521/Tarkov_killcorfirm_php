<?php
//include the html style links and head
include './layout/head.php';



// Made for Unknowncheats for escape from tarkov section 
// can be used for other games ofcourse
// feel free to use it for other projects
// https://github.com/529521/Tarkov_killcorfirm_php
class Kills
{
    //create the variables
    public $kills = 0;
    public $d;
    public $username;
    public $auto_delete = "1";

   

    // set the directory to ure escape from tarkov nvidia highlight folder
    //default folder =  C:\Users\( windows user name here )\AppData\Local\Temp\Escape From Tarkov
    // check around line: 123 to change manualy
    public function set_d($d)
    {
        $this->d = $d;
    }

    // set the current username for your directory
    // check around line: 123 to change manualy
    public function set_username($username)
    {
        $this->username = $username;
    }

    //GET the current autodelete value
    //it will delete all the kill nvidia highlights in the this folder:
    // C:\Users\( windows user name here )\AppData\Local\Temp\Escape From Tarkov
    // those files also get auto deleted when ure raid ends(its a nvidia feauture)
    // "0" = off
    // "1" = on
    // check around line: 120 to change manualy
    public function get_autodelete()
    {
        return $this->auto_delete;
    }


    //SET the current autodelete value
    //it will delete all the kill nvidia highlights in the this folder:
    // C:\Users\( windows user name here )\AppData\Local\Temp\Escape From Tarkov
    // those files also get auto deleted when ure raid ends(its a nvidia feauture)
    // "0" = off
    // "1" = on
    // check around line: 120 to change manualy  
      public function set_autodelete($auto_delete)
    {
        $this->auto_delete = $auto_delete;
    }




    //get the current username(windows user name for path)
    public function get_username()
    {
        return $this->username;
    }
    //get the current kills
    public function get_kills()
    {
        return $this->kills;
    }

    //Main function for the application 
    //checks all the kills and files
    public function checkkills()
    {
        //check for kills
        if ($handle = opendir($this->d)) {

            while (($file = readdir($handle)) !== false) {
                if (!in_array($file, array('.', '..')) && !is_dir($this->d . $file)) {
                    $this->kills++;
                }

            }
        }

        //check for nvidia highlights file change
        if ($handle = opendir($this->d)) {

            while (false !== ($entry = readdir($handle))) {
        //loop to get all the files
        //get all the filles and make the 1:1 ratio with the kill confirmed cards
                if ($entry != "." && $entry != "..") {
                   
                    echo "
                  <div class='card' style='width: 16rem;'>
                    <div class='card-body'>
                  <h5 class='card-title'>Kill Confirmed</h5>
                  <h6 class='card-subtitle mb-2 text-muted'>You have a kill</h6>
                  </div>
                  </div>";
                 //refresh to check every 2 sec

                    header("refresh: 2");

                }
                // Delete all files from nvidia hightlight to save space
                if ($this->auto_delete == "1") {
                    if ($this->kills > 9) {
                        $del_files = glob($this->d . "/*"); // get all file names
                        foreach ($del_files as $del_file) { // iterate files
                            if (is_file($del_file)) {
                                unlink($del_file);
                            }
                            // delete file
                        }
                    }
                }

            }
            closedir($handle);
            //refresh to check every 2 sec
            header("refresh: 2");

        }

    }

}

//default status value
$status = "turned off";
$status_delete = "Auto reset kill after 10 kills to save pc space";

// switch on
if (isset($_GET['switch_on'])) {
    $kill_counter = new Kills();

    // set to 0 to disable auto deleting of files after 10 kills
    $kill_counter->set_autodelete("1");

    // if it cant get the env username ( windows user name)
    // Manualy put ure name like this 
    // $kill_counter->set_d("C:\Users\karen\AppData\Local\Temp\Escape From Tarkov");
    $kill_counter->set_username(getenv("username"));
    $get_username = $kill_counter->get_username();
    $kill_counter->set_d("C:\Users\./$get_username\AppData\Local\Temp\Escape From Tarkov");

    //check the kills function
    $kill_counter->checkkills();

    //update status from the checking of kill and the status of the auto delete ( see around line 101 to turn off auto delete)
    $status = "checking kills";
    if ($kill_counter->get_autodelete() == "0") {
        $status_delete = "Auto reset kill DISABLED";

    }

}

// switch off
if (isset($_GET['switch_off'])) {
    $kill_counter = new Kills();
    $status = "Turned off";
    if ($kill_counter->get_autodelete() == "0") {
        $status_delete = "Auto reset kill DISABLED";

    }

}
// Litte dashboard for on / off functions and the kill counter
?>
<div class="switch">
  <p>
    <?php
// shows the total amount of kill confirmed
if (isset($_GET['switch_on'])) {

    echo $kill_counter->get_kills() . " kills confirmed";}
?>
  </p>
  <a class="btn" id="on" href='kill.php?switch_on=true'>Check for kills</a>
  <a class="btn" id="off" href='kill.php?switch_off=true'>Stop checking</a>

  <p>
<?php 
//status on / off 
  echo $status . " ...";
?></p>

</div>
</html>
