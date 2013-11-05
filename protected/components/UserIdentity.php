<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;
    private $_roles;
    private $_logged;

    public function authenticate() {
        $username = strtolower($this->username);
        $user = User::model()->find('LOWER(username)=?', array($username));
        
        if ($user === null) {
            $user = User::model()->find('LOWER(email)=?', array($username));
        }

        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$user->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $entity = Entity::get($user);
            $this->_id = $entity->id;
            $this->username = $user->username;
            $this->errorCode = self::ERROR_NONE;
            $user->saveAttributes(array('last_login' => date('YmdHis')));
            ActivityLog::add($this->_id, ActivityLog::LOGIN);

            $roles = Role::model()->with('part')->findAll('actor_id=' . $this->_id . ' AND deleted is null');
            $rolesArray = Array();
            $rolesArray[$this->_id] = $this->username;
            foreach ($roles as $role) {
                $rolesArray[$role->part_id] = $role->part->name;
            }
            $this->setState('roles', $rolesArray);
            $this->setState('logged', $this->_id);
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        if (isset($this->_logged[$id])) {
            $this->_id = $id;
            $this->username = $this->_logged[$id];
        }
    }

    public function getLogged() {
        return $this->_logged;
    }

    /* public function getRoles() {
      return $this->_roles;
      }
      public function setRoles() {
      return $this->_roles;
      } */
}
