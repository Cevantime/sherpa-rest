<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 11/10/18
 * Time: 19:24
 */

namespace Sherpa\Rest\Validator;


use Sherpa\Rest\Utils\Bag;

class InputBag implements \ArrayAccess
{
    /**
     * @var Bag
     */
    protected $data;
    /**
     * @var Bag
     */
    protected $uploadedFiles;
    /**
     * @var Bag
     */
    protected $errors;

    /**
     * Validator constructor.
     * @param Bag $data
     * @param Bag $uploadedFiles
     */
    public function __construct(array $data, array $uploadedFiles = [])
    {
        $this->data = new Bag($data);
        $this->uploadedFiles = new Bag($uploadedFiles);
        $this->errors = new Bag([]);
    }

    /**
     * @return Bag
     */
    public function getSentData(): Bag
    {
        return $this->data;
    }

    /**
     * @param Bag $data
     */
    public function setData(Bag $data): void
    {
        $this->data = $data;
    }

    /**
     * @return Bag
     */
    public function getUploadedFiles(): Bag
    {
        return $this->uploadedFiles;
    }

    /**
     * @param Bag $uploadedFiles
     */
    public function setUploadedFiles(Bag $uploadedFiles): void
    {
        $this->uploadedFiles = $uploadedFiles;
    }

    /**
     * @return Bag
     */
    public function getErrors(): Bag
    {
        return $this->errors;
    }

    /**
     * @param Bag $errors
     */
    public function setErrors(Bag $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]) || isset($this->uploadedFiles[$offset]) || isset($this->errors[$offset]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? $this->uploadedFiles[$offset];
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}