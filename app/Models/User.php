<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    // protected $fillable = [
    //     'firstname',
    //     'middlename',
    //     'lastname',
    //     'email',
    //     'password',
    //     'phone',
    //     'gender',
    //     'address',
    //     'school',
    //     'starting_date',
    //     'emergency_fullname',
    //     'emergency_contact',
    //     'emergency_address',
    //     'qr_code',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function history(): HasMany
    {
        return $this->hasMany(Histories::class, 'user_id');
    }

    public function search($query)
    {
        return empty($query) ? $this->query() : $this->query()->where('firstname', 'like', "%{$query}%")
            ->orWhere('lastname', 'like', "%{$query}%")
            ->orWhere('student_no', 'like', "%{$query}%");
    }

    public function downloadRequests()
    {
        return $this->hasMany(DtrDownloadRequest::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function profiles()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }


    //smm relations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function issuedJobOrders()
    {
        return $this->hasMany(JobOrder::class, 'issued_by');
    }

    /**
     * Get job drafts where this user is assigned as a content writer.
     */
    public function contentWriterJobs()
    {
        return $this->hasMany(JobDraft::class, 'content_writer_id');
    }

    /**
     * Get job drafts where this user is assigned as a graphic designer.
     */
    public function graphicDesignerJobs()
    {
        return $this->hasMany(JobDraft::class, 'graphic_designer_id');
    }

    /**
     * Get job drafts where this user is assigned as a client.
     */
    public function clientJobs()
    {
        return $this->hasMany(JobDraft::class, 'client_id');
    }

    public function declinedRevisions()
    {
        return $this->hasMany(Revision::class, 'declined_by');
    }

    public function requestForms()
    {
        return $this->hasMany(RequestForm::class, 'requested_by');
    }

    /**
     * Relationship: User has many RequestForms as the manager.
     */
    public function managedRequests()
    {
        return $this->hasMany(RequestForm::class, 'manager_id');
    }

    /**
     * Relationship: User has many RequestForms as the receiver.
     */
    public function receivedRequests()
    {
        return $this->hasMany(RequestForm::class, 'receiver_id');
    }
}