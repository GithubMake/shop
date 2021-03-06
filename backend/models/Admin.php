<?php

namespace backend\models;


use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $auth_key
 * @property string $password_hash 密码
 * @property string $password_reset_token 重置密码
 * @property string $email 邮箱
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property string $last_login_time 最后登录时间
 * @property string $last_login_ip 最后登录ip
 */
class Admin extends ActiveRecord implements IdentityInterface
{

    public $roles;//用于RBAC的用户和角色的关联
    public $password;//保存明文密码,用于区分密文密码
    const SCENARIO_ADD = 'add';//定义添加场景常量
    const SCENARIO_EDIT = 'edit';//定义修改场景常量

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username',   'email','status'], 'required'],
            [['status'], 'integer'],
            [['username',  'email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            ['password','required', 'on'=>[self::SCENARIO_ADD]],//在添加场景必填
            ['password','safe', 'on'=>[self::SCENARIO_EDIT]],//在修改场景可不填
            ['roles','safe']//必须填一个角色
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'email' => '邮箱',
            'status' => '状态',
            'roles'=>'角色',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $authKey== $this->auth_key;
    }


    public function beforeSave($insert)
    {
        if($insert){//添加
            $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password);//对密码进行加密
            $this->last_login_time = time();
            $this->last_login_ip= \Yii::$app->request->getRemoteIP();
            $this->password_reset_token = 0;
            $this->created_at = time();
            $this->auth_key = \Yii::$app->security->generateRandomString();//生成随机字符串
        }else{//修改
            $this->updated_at = time();
        }

        return parent::beforeSave($insert);
    }




    public  static  function getRoles(){
        $authManager = \Yii::$app->authManager;//组件实例化
        $roles = $authManager->getRoles();//获取角色
        $items = [];
        foreach ($roles as $role){
            $items[$role->name] = $role->name;//拼凑角色
        }
        return $items;
    }
}
