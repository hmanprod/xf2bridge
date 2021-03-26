<?php

namespace swede2k\XF2Bridge;

use swede2k\XF2Bridge\Visitor\VisitorInterface;
use swede2k\XF2Bridge\Visitor\Visitor;


class XF2Bridge
{
    /**
    * Absolute Path to Xenforo Directory
    * (ex. /home/username/www/forums/ )
    *
    * @var string
    */
    protected $directoryPath;

    /**
    * Base Url to Xenforo Application
    * (ex. http://example.com/forums | http://example.com)
    *
    * @var string
    */
    protected $baseUrl;

    /**
    * @var VisitorInterface
    */
    protected $visitor;

    /**
    * Bootstrap XF2Bridge
    *
    * @param $directoryPath
    * @param $baseUrl
    * @throws \Exception
    */
    public function __construct($directoryPath, $baseUrl)
    {
        $this->directoryPath = $directoryPath;
        $this->baseUrl = $baseUrl;
        //load Xenforo 2 app
        $this->bootstrapXenforo();
    }

    /**
    * Bootstrap Xenforo 2 Application
    * @throws \Exception
    */
    protected function bootstrapXenforo()
    {
        $path = $this->directoryPath . '/src/XF.php';
        if( file_exists($path) && is_readable($path) && require_once($path)) {
            \XF::start($this->directoryPath);
            $app = \XF::setupApp('XF\Pub\App');
            $app->start();
	    //$app = \XF::app();
        } else
            throw new \Exception('Could not load XenForo check path: ' . $path);
    }

    /**
     * Retrieve Visitor Class
     *
     * @return VisitorInterface
     */
    public function retrieveVisitor()
    {

        if(!$this->visitor instanceof VisitorInterface)
        {
            $this->setVisitor(new Visitor);
        }

        return $this->visitor;
    }

    /**
     * Set a new Visitor implementation
     *
     * @param VisitorInterface $visitor
     */
    public function setVisitor(VisitorInterface $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Gets singleton instance of Xenforo_Visitor
     *
     * @return \XenForo_Visitor
     */
    public function getVisitor()
    {
        return $this->retrieveVisitor()->getCurrentVisitor();
    }

    /**
     * Get Xenforo Bridge Visitor Object
     * (use instead of geting Xenforo_Visitor class)
     *
     * @return VisitorInterface
     */
    public function getVisitorObject()
    {
        return $this->retrieveVisitor();
    }
	
    /**
     * Checks if current visitor is moderator
     *
     * @return boolean
     */

     public function isModerator()
	{
		return $this->getVisitor()->is_moderator;
	}

    /**
     * Checks if current visitor is banned
     *
     * @return boolean
     */
    public function isBanned()
    {
        return (bool)$this->retrieveVisitor()->isBanned();
    }

    /**
     * Checks if current visitor is an Admin
     *
     * @return boolean
     */
	public function isAdmin()
	{
		return $this->retrieveVisitor()->isAdmin();
	}

    /**
     * Checks if visitor is a Super Admin
     *
     * @return boolean
     */
	public function isSuperAdmin()
	{
		return $this->retrieveVisitor()->isSuperAdmin();
	}

    /**
     * Checks if visitor is currently logged in
     *
     * @return boolean
     */
	public function isLoggedIn()
	{
		return $this->retrieveVisitor()->isLoggedIn();
	}

    /**
     * Retrieve the current Visitors User id
     *
     * @return mixed
     */
    public function getVisitorUserId()
    {
       return $this->getVisitor()->getUserId();
    }
    /**
     * Checks if visitor has a particular permission
     *
     * @param $group - permission group
     * @param $permission - permission
     * @return mixed
     */
    public function hasPermission($group,$permission)
    {
        return $this->retrieveVisitor()->hasPermission($group,$permission);
    }

    /**
     * Retrieves XenForo Session
     *
     * @return mixed
     * @throws \Zend_Exception
     */
    public function getSession()
    {
        return \XF::session();
    }
}
