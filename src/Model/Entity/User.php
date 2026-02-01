<?php
namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        '*' => true, // Allow mass assignment for all fields
        'id' => false // Prevent mass assignment for the primary key
    ];

    /**
     * Fields that are excluded from JSON and array representations of the entity.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'password' // Prevent password from being exposed in serialized output
    ];

    /**
     * Hash the password before saving it to the database.
     *
     * @param string|null $password The password to hash.
     * @return string|null The hashed password or null if no password is provided.
     */
    protected function _setPassword(?string $password): ?string
    {
        if ($password === null || $password === '') {
            return null;
        }

        return (new DefaultPasswordHasher())->hash($password);
    }
}