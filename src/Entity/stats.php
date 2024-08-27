<?php

namespace App\Entity;

class stats {


/**
 * @var string|null
 */
private $annees;


/**
 * Get the value of annees
 *
 * @return  string|null
 */ 
public function getAnnees()
{
return $this->annees;
}

/**
 * Set the value of annees
 *
 * @param  string|null  $annees
 *
 * @return  self
 */ 
public function setAnnees($annees)
{
$this->annees = $annees;

return $this;
}

public function __toString()
{
  return  $this->getAnnees();
}
}

