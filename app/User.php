<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\CompanyBoundaryScope1;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password',
        'role',
        'first_name',
        'last_name',
        'business_name',
        'email',
        'address1',
        'address2',
        'suburb',
        'state',
        'postcode',
    ];

    public function getDescriptionAttribute()
    {
        if ($this->business_name != '') {
            return $this->business_name;
        }
        else {
            return $this->full_name;
        }
    }

    public function getFullNameAttribute() {
        return $this->first_name.' '.$this->last_name;
    }

    public function getAddressAttribute() {
        return $this->address1.' '.(($this->address2 != '')?$this->address2.' ':'').$this->suburb.' '.$this->state.' '.$this->postcode;
    }

    public function getAddressMultiAttribute()
    {
        return (($this->business_name != '')?$this->business_name.'<br>':'').
            $this->address1.'<br>'.
            (($this->address2 != '')?$this->address2.'<br>':'').
            $this->suburb.' '.$this->state.' '.$this->postcode;
    }

    // intercept setting of email to ensure it's saved as lowercase
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->isSuperAdmin();
    }

    public function isUser()
    {
        return !$this->isAdmin();
    }

    private function hasRole($roleDescription)
    {
        return $this->roles()->where('description', $roleDescription)->exists();
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    // user has many invoices, but note foreign key in invoice table is
    // customer_id so specify that explicity in hasMany relationship
    public function invoices()
    {
        return $this->hasMany('App\Invoice', 'customer_id');
    }

    public function getRoleAttribute()
    {
        if ($this->isSuperAdmin()) { return 'super_admin'; }
        if ($this->isAdmin()) { return 'admin'; }
        if ($this->isUser()) { return 'user'; }
    }

    // update roles for the user
    public function setRoleAttribute($value)
    {
        $this->roles()->detach();
        switch ($value)
        {
            case 'super_admin':
                $this->roles()->attach(1);
                break;
            case 'admin':
                $this->roles()->attach(2);
                break;
        }
    }

    /**
     * Return ordered list of users for use in select elements
     */
    public function userSelectList()
    {
        return User::where('company_id', '=', $this->company_id)->get()->sortBy('description')->lists('description', 'id');
    }

    // return name of logo image
    public function getLogoFilenameAttribute()
    {
        return 'logo'.$this->company_id.'.img';
    }

    /**
	 * Setup the relationship to company
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function company()
	{
		return $this->belongsTo('App\Company', 'company_id');
	}

    public static function createWithCompany(array $attributes = [], $company_id)
    {
        $attributes['company_id'] = $company_id;
        return parent::create($attributes);
    }
}
