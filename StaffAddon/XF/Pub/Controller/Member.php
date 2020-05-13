<?php

namespace StaffAddon\XF\Pub\Controller;

use XF\Entity\User;
use XF\Entity\UserProfile;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View;

class Member extends XFCP_Member
{
    public function actionIndex(ParameterBag $params)
    {
        $response = parent::actionIndex($params);
        $key = $this->filter('key', 'str');

        $staff = $this->finder('XF:User')->where('is_staff', '1')->fetch();
        $allStaff = array(
            "Administrator" => array(),
            "SrDeveloper" => array(),
            "Developer" => array(),
            "T-Developer" => array(),
            "SrModerator" => array(),
            "Moderator" => array(),
            "T-Moderator" => array(),
            "SrSupporter" => array(),
            "Supporter" => array(),
            "T-Supporter" => array(),
            "SrContent" => array(),
            "Content" => array(),
            "SrBuilder" => array(),
            "Builder" => array(),
            "T-Builder" => array(),
        );

        $requiredUserGroups = array_keys($allStaff);

        foreach ($staff as $member) {
            $title = $this->getUserGroupNameById($member->user_group_id);
            if (in_array($title, $requiredUserGroups)) {
                $allStaff[$title][] = $member;
            }

        }

        if ($response instanceof View) {
            $response->setParams([
                'allStaff' => $allStaff,
                'key' => $key
            ]);
        }


        return $response;
    }

    protected function getUserGroupNameById($id)
    {
        return $this->finder('XF:UserGroup')->where('user_group_id', $id)->fetchOne()->title;
    }
}
?>