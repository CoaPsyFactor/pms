<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BlogCategory
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogPost[] $posts
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategory whereUpdatedAt($value)
 */
	class BlogCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BlogPost
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $user_id
 * @property boolean $active
 * @property integer $category_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogPost whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogPost whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogPost whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogPost whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogPost whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogPost whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogPost whereUpdatedAt($value)
 */
	class BlogPost extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NavigationLink
 *
 * @property integer $id
 * @property integer $parent
 * @property string $title
 * @property string $slug
 * @property boolean $internal
 * @property string $value
 * @property boolean $is_active
 * @property integer $sort
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereParent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereInternal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereSort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\NavigationLink whereUpdatedAt($value)
 */
	class NavigationLink extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Page
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $user_id
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Page whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Page whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Page whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Page whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Page whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Page whereUpdatedAt($value)
 */
	class Page extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Plugin
 *
 * @property integer $id
 * @property string $base_class
 * @property string $name
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Plugin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Plugin whereBaseClass($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Plugin whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Plugin whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Plugin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Plugin whereUpdatedAt($value)
 */
	class Plugin extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogPost[] $posts
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

