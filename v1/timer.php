<?php

class Timer {

    //set paramether seconds, default is 0
    protected $seconds, $filename;

    public function __construct($seconds = 0, $filename) {
        $this->seconds = $seconds;
        $this->filename = $filename;
    }

   

    private function touchFile() {

        if (file_exists($this->filename)) {
            touch($this->filename);
        } else {
            fclose(fopen($this->filename, 'a'));
        }
    }

    private function lastModified() {
        if (file_exists($this->filename)) {
            return (int) filectime($this->filename);
        }
    }

    public function setTimer() {
        $valueDiff = $this->getTimerValue();
        if ($valueDiff >= $this->seconds) {
            return $this->resetTimer();
        }
    }

    private function resetTimer() {

        return $this->touchFile();
    }

    public function checkTimer() {  
        $valueDiff = $this->getTimerValue();
        if ($valueDiff >= $this->seconds)
            return TRUE;
        else
            return FALSE;
    }

    private function getDifference($val1, $val2) {
        return $val1-$val2;
    }

    public function getTimerValue() {
        $modified = $this->lastModified();
        $now = (int) time();
        $diff = $this->getDifference($now, $modified);
        return $diff;
    }

}
?>