<?php
namespace Jankx\TemplateEngine;

use ReflectionFunction;

class FunctionWrapper
{
    protected $fn;
    protected $args;
    protected $use_ob;

    public function __construct($function_name, $args = array(), $return_output_buffer = false)
    {
        if (is_callable($function_name)) {
            $this->fn = $function_name;
            $this->args = $args;
            $this->use_ob = $return_output_buffer;
        }
    }

    public function __toString()
    {
        if (is_null($this->fn)) {
            return '';
        }
        try {
            $result = $this->execute();
            if (is_array($result)) {
                return implode(' ', $result);
            }
            return (string) $result;
        } catch (\Exception $e) {
            return 'Caught exception: '.$e->getMessage()."\n";
        }
    }

    public function execute()
    {
        if (!$this->use_ob) {
            $reflect = new ReflectionFunction($this->fn);
            $numOfParams = count($reflect->getParameters());

            return call_user_func_array($this->fn, $this->args);
        }

        ob_start();
        call_user_func_array($this->fn, $this->args);

        return ob_get_clean();
    }
}
