<?php
namespace Jankx\TemplateEngine\Engines;

class WordPress extends Plates 
{
  const ENGINE_NAME = 'wordpress';
  
  public function isDirectRender()
  {
      return true;
  }
}
