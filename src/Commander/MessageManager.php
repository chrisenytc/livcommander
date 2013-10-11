<?php namespace Commander;

class MessageManager {
	
	/*
    |--------------------------------------------------------------------------
    | LivCommander -- Module MessageManager
    |--------------------------------------------------------------------------
    |
    | The LivCommander provides an interface for managing tasks. 
    | Some basic functions such as installation 
    | and maintenance is one of its features.
    |
    |
    */

    //Messages Path
    private $appname = 'LivCommander';
    //Messages Path

    //Messages Path
    private $path;
    private $message_path;
    //Messages Path

    public function __construct()
    {
        //Check if the path is defined
        if(empty($this->path))
        {
            $this->message_path = __DIR__.DIRECTORY_SEPARATOR.'messages'.DIRECTORY_SEPARATOR;
        }
        else
        {
            $this->message_path = $this->path;
        }
    }

    /**
     * Provide a AppNameManager.
     *
     * @param string $name
     * @return void
     */
    public function setAppName($name)
    {
        $this->appname = $name;
    }

    /**
     * Get AppName.
     *
     * @return string
     */
    public function getAppName()
    {
        return $this->appname;
    }

    /**
     * Provide a MessagePathManager.
     *
     * @param string $path
     * @return void
     */
    public function setMessagePath($path)
    {
        $this->path = $path;
    }

    /**
     * Get Message Path.
     *
     * @return string
     */
    public function getMessagePath()
    {
        return $this->message_path;
    }

    /**
     * Provide a FileReader.
     *
     * @param string $filename
     * @return string
     */
    private function fileReader($filename)
    {
        //Read message file and return content
        $message = file_get_contents($this->message_path.$filename.'.livia');
        //Return the message
        return $message;
    }

    /**
     * Provide a ColorManager.
     *
     * @param string $text
     * @param string $status
     * @return string
     */
    protected function colorize($text, $status) {
    //Output
     $out = "";
     //Check status
     switch($status) {
      case "success":
       $out = "[42m"; //Green background
       break;

      case "danger":
       $out = "[41m"; //Red background
       break;

      case "warning":
       $out = "[43m"; //Yellow background
       break;

      case "info":
       $out = "[44m"; //Blue background
       break;

      default:
       throw new Exception("Invalid status: " . $status);
     }
     return chr(27) . "$out" . "$text" . chr(27) . "[0m";
    }

    /**
     * Provide a CommandManager.
     *
     * @param string $cmd
     * @return boolean
     */
    protected function commandExists($cmd) {
        //Check if the command return a value
        $returnVal = shell_exec("which $cmd");
        //If command not found return false 
        //or if command exists return true
        return (empty($returnVal) ? false : true);
    }


    /**
     * Provide a MessageManager.
     *
     * @param string $name
     * @return string
     */
    protected function getMessage($name)
    {
        //Get required message
        $message = $this->fileReader($name);
        //Show required message
        echo "\n";
        echo $message;
        echo "\n";
    }

    /**
     * Provide a NotificationManager.
     *
     * @param string $name
     * @param boolean $status
     * @return string
     */
    protected function getNotification($name, $status = FALSE)
    {
        //If $status is FALSE show primary notification
        if($status === FALSE)
        {
            //Generate a message
            if($this->commandExists('notify-send'))
            {
                shell_exec('notify-send "'.$this->getAppName().' Tasks"  "Running '.$name.'..." -i dialog-information');
            }
            //Show required message
            echo "\n";
            echo "\t", $this->colorize('Running '.$name.'...', 'info');
            echo "\n";
        }
        else
        {
            //If $status is TRUE show success notification
            //Generate a message
            if($this->commandExists('notify-send'))
            {
                shell_exec('notify-send "'.$this->getAppName().' Tasks" "'.$name.' Task Completed!" -i dialog-ok');
            }
            //Show required message
            echo "\n";
            echo "\t", $this->colorize($name.' Task Completed!', 'success');
            echo "\n";
        }
    }

    /**
     * Provide a LogManager.
     *
     * @param string $text
     * @param string $status
     * @return string
     */
    public function log($text, $status)
    {
        //Show required message
        echo "\n";
        echo "\t", $this->colorize($text, $status);
        echo "\n";
    }

    /**
     * Provide a AskManager.
     *
     * @param string $question
     * @return string
     */
    public function ask($question)
    {
        //Show required message
        echo "\n";
        echo "\t", $this->colorize($question, 'info');
        echo "\n";
        //Get user input
        echo "\t"; $response = trim(fgets(STDIN));

        return $response;
    }

    /**
     * Provide a ConfirmManager.
     *
     * @param string $question
     * @return string
     */
    public function confirm($question, $default = 'yes')
    {
        //Show required message
        echo "\n";
        echo "\t", $this->colorize($question." [$default] ", 'info'); echo " ";
        //Get user input
        $response = trim(fgets(STDIN));
        //Check if $response is yes or not
        if($response == 'yes' || $response == 'no')
        {
            //If is yes or not return the response
            return $response;
        }
        else if(empty($response))
        {
            //If response is empty return default value
            return $default;
        }
        else
        {
            //If not passed argument, repeat confirmation.
            $this->confirm($question, $default);
        }
    }
}