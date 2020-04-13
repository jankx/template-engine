<?php
namespace Jankx\Template\Engine;

class WordPress extends Engine
{
    protected $defaultTemplateDir;
    protected $directoryInTheme;
    protected $extension = 'php';

    public function __construct($args)
    {
        $this->setDefaultTemplateDir($args['template_location']);
        $this->setDirectoryInTheme($args['template_directory']);
    }

    public function setDefaultTemplateDir($dir)
    {
        $this->defaultTemplateDir = apply_filters(
            "jankx_template_{$dir}_default_directory",
            $dir
        );
    }

    public function setDirectoryInTheme($dirName)
    {
        $this->directoryInTheme = $dirName;
    }

    public function render($templates, $data = [], $echo = true)
    {
        $searchedTemplates = [];
        $is_mobile = apply_filters('jankx_is_mobile_template', wp_is_mobile());

        foreach ((array)$templates as $template) {
            $tmp = '%1$s.%2$s';
            if ($this->directoryInTheme !== '') {
                $tmp = '%3$s/%1$s.%2$s';
            }
            if ($is_mobile) {
                $searchedTemplates[] = sprintf($tmp, $template, $this->extension, 'mobile');
            }
            $searchedTemplates[] = sprintf($tmp, $template, $this->extension, $this->directoryInTheme);
        }
        $template = locate_template($searchedTemplates, false);
        if (!$template) {
            $template = $this->searchDefaultTemplate($templates);
        }

        if (empty($template)) {
            return;
        }

        extract($data);
        if (!$echo) {
            ob_start();
            require $template;
            return ob_get_clean();
        }
        require $template;
    }

    public function searchDefaultTemplate($templates)
    {
        foreach ((array)$templates as $template) {
            $templateFile = sprintf('%s/%s.%s', $this->defaultTemplateDir, $template, $this->extension);
            if (file_exists($templateFile)) {
                return $templateFile;
            }
        }
    }
}
