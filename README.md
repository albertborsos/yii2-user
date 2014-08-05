## Yii2 user module

### How to use:
1. Install it via composer `albertborsos/yii2-user`

2. Run sql script from the `tests/_data` folder (yii migrate will available) (delete my user)

3. add these snippet to your `config/main.php` file

```php
    'components' => [
    ...
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'itemChildTable' => 'auth_item_child',
            'assignmentTable' => 'auth_assignment',
            'ruleTable' => 'auth_rule',
            'defaultRoles' => ['guest'],
        ],
        'user' => [
            'identityClass' => 'albertborsos\yii2user\models\Users',
            'enableAutoLogin' => false,
            'loginUrl' => ['users/login'],
        ],
    ...
    ]
```

4. register yourself than modify your right to admin in `auth_assignment` table