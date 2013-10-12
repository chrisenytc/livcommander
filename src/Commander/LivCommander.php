<?php namespace Commander;

class LivCommander extends MessageManager {
    
    /*
    |--------------------------------------------------------------------------
    | LivCommander Interface
    |--------------------------------------------------------------------------
    |
    | The LivCommander provides an interface for managing tasks. 
    | Some basic functions such as installation 
    | and maintenance is one of its features.
    |
    |
    */

    //LivCommander Version
    const VERSION = '1.0.1';
    //LivCommander Version

    //Options List
    private $options;
    //Options List

    //Constructor
    public function __construct()
    {
        $this->options = array();
    }

    /**
     * Display a livbox version.
     *
     * @return string
     */
    public static function version()
    {
        echo self::VERSION;
        echo "\n";
    }

    /**
     * Check if array index exists.
     * 
     * @param string $input
     * @return boolean
     */
    private function hasInput($input)
    {
        //Check if the input exists
        if(array_key_exists(1, $input))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Provide a ProcessManager.
     * @param string $name
     * @param string $command
     * @return string
     */
     private function process($name, $command)
     {
        //Get First Notification
        $this->getNotification($name);
        //Check if the command exists
        if($this->commandExists($command))
        {
            //Execute this command
            system($command);
            //Get Success Notification
            $this->getNotification($name, TRUE);
        }
        else if($name == 'composer')
        {
            //If command composer not found, install composer
            system('curl -sS https://getcomposer.org/installer | php');
            //Run composer install
            system('php composer.phar install');
            //Get Success Notification
            $this->getNotification($name, TRUE);
        }
        else
        {
            //If command not found show error
            $this->log('Command '.$command.' Not Found!', 'danger');
            exit();
        }

     }

     /**
     * Add option in options list.
     *
     * @param string $name
     * @param string $description
     * @param array $data
     * @return void
     */
     public function setOption($name, $description, array $data)
     {
        //Set option
        $this->options[] = array('name' => $name, 'description' => $description, 'data' => $data);
     }

    /**
     * return options list.
     *
     * @return array
     */
     public function getOptions()
     {
        //Get option
        $out = array();
        foreach ($this->options as $opt) {
            $out[] = $opt['name'];
        }
        return $out;
     }

     /**
     * Show Commands List.
     *
     * @return void
     */
     private function showlist()
     {
        $this->getMessage('help');

        foreach ($this->options as $opt) {
            echo "\n";
            echo "\t \t \t", $this->log(" ".$opt['name']." => ".$opt['description']." ", 'success');
            echo "\n";
        }
     }

     /**
     * Provide a start application.
     *
     * @param string $input
     * @return void
     */
     public function start($input)
     {
        //Get Welcome Message
        $this->getMessage('welcome');
        //Check If shell input exists
        if($this->hasInput($input))
        {
            //If shell input exists $in receive
            $in = $input[1];
            //Show Tasks start
            $this->getMessage('starttask');
            //Browse all the options array.
            foreach ($this->options as $opt) {
                //If command exists in options list
                if(in_array($in, $opt))
                {
                    //Browse all the command array.
                    foreach ($opt['data'] as $value) {
                        //Run process with this command
                        $this->process($value['name'], $value['command']);
                    }
                }
                else if($in == 'help')
                {
                    //If shell input is help show list
                    $this->showlist();
                    break;
                }
                else
                {
                    //If command not exists show error
                   $this->log('Command '.$in.' Not Found!', 'danger');
                    exit(); 
                }
            }
            //Show Tasks end
            $this->getMessage('endtask');
        }
        else
        {
            //If Shell input is not exists
            $in = '';

            //If command not exists show error
            $this->log('Command '.$in.' Not Found!', 'danger');
            exit();
        }

        $this->getMessage('end');
     }
}
