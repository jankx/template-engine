<?php
namespace Jankx\TemplateEngine;

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
            return (string) $this->execute();
        } catch (\Exception $e) {
            var_dump(call_user_func($this->fn));
            die;
            return 'Caught exception: '.$e->getMessage()."\n";
        }
    }

    public function execute()
    {
        if (!$this->use_ob) {
            return call_user_method_array($this->fn, $this->args);
        }

        ob_start();
        call_user_func_array($this->fn, $this->args);

        return ob_get_clean();
    }
}
