<?php
/**
 * PHP Reader Library
 *
 * Copyright (c) 2008 The PHP Reader Project Workgroup. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *  - Neither the name of the project workgroup nor the names of its
 *    contributors may be used to endorse or promote products derived from this
 *    software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    php-reader
 * @subpackage ID3
 * @copyright  Copyright (c) 2008 The PHP Reader Project Workgroup
 * @license    http://code.google.com/p/php-reader/wiki/License New BSD License
 * @version    $Id: USER.php 65 2008-04-02 15:22:46Z svollbehr $
 */

/**#@+ @ignore */
//require_once("ID3/Frame.php");
//require_once("ID3/Encoding.php");
//require_once("ID3/Language.php");
/**#@-*/

/**
 * The <i>Terms of use frame</i> contains a brief description of the terms of
 * use and ownership of the file. More detailed information concerning the legal
 * terms might be available through the {@link com_googlecode_phpreader_id3_frame_WCOP} frame. Newlines
 * are allowed in the text. There may be more than one Terms of use frames in a
 * tag, but only one with the same language.
 *
 * @package    php-reader
 * @subpackage ID3
 * @author     Sven Vollbehr <svollbehr@gmail.com>
 * @copyright  Copyright (c) 2008 The PHP Reader Project Workgroup
 * @license    http://code.google.com/p/php-reader/wiki/License New BSD License
 * @version    $Rev: 65 $
 */
final class com_googlecode_phpreader_id3_frame_USER extends com_googlecode_phpreader_id3_Frame
  implements com_googlecode_phpreader_id3_Encoding, ID3_Language
{
  /** @var integer */
  private $_encoding = com_googlecode_phpreader_id3_Encoding::UTF8;
  
  /** @var string */
  private $_language = "eng";

  /** @var string */
  private $_text;
  
  /**
   * Constructs the class with given parameters and parses object related data.
   *
   * @param Reader $reader The reader object.
   */
  public function __construct($reader = null)
  {
    parent::__construct($reader);
    
    if ($reader === null)
      return;
    
    $this->_encoding = com_googlecode_phpreader_Transform::fromInt8($this->_data[0]);
    $this->_language = substr($this->_data, 1, 3);
    $this->_data = substr($this->_data, 4);

    switch ($this->_encoding) {
    case self::UTF16:
      $this->_text = com_googlecode_phpreader_Transform::fromString16($this->_data);
      break;
    case self::UTF16BE:
      $this->_text = com_googlecode_phpreader_Transform::fromString16BE($this->_data);
      break;
    default:
      $this->_text = com_googlecode_phpreader_Transform::fromString8($this->_data);
    }
  }
  
  /**
   * Returns the text encoding.
   * 
   * @return integer
   */
  public function getEncoding() { return $this->_encoding; }
  
  /**
   * Sets the text encoding.
   * 
   * @see com_googlecode_phpreader_id3_Encoding
   * @param integer $encoding The text encoding.
   */
  public function setEncoding($encoding) { $this->_encoding = $encoding; }
  
  /**
   * Returns the language code as specified in the
   * {@link http://www.loc.gov/standards/iso639-2/ ISO-639-2} standard.
   * 
   * @return string
   */
  public function getLanguage() { return $this->_language; }
  
  /**
   * Sets the text language code as specified in the
   * {@link http://www.loc.gov/standards/iso639-2/ ISO-639-2} standard.
   * 
   * @see ID3_Language
   * @param string $language The language code.
   */
  public function setLanguage($language) { $this->_language = $language; }
  
  /**
   * Returns the text.
   * 
   * @return string
   */
  public function getText() { return $this->_text; }
  
  /**
   * Sets the text using given language and encoding.
   * 
   * @param string $text The text.
   * @param string $language The language code.
   * @param integer $encoding The text encoding.
   */
  public function setText($text, $language = false, $encoding = false)
  {
    $this->_text     = $text;
    if ($language !== false)
      $this->_language = $language;
    if ($encoding !== false)
      $this->_encoding = $encoding;
  }
  
  /**
   * Returns the frame raw data.
   *
   * @return string
   */
  public function __toString()
  {
    $data = com_googlecode_phpreader_Transform::toInt8($this->_encoding) . $this->_language;
    switch ($this->_encoding) {
    case self::UTF16:
      $data .= com_googlecode_phpreader_Transform::toString16($this->_text);
      break;
    case self::UTF16BE:
      $data .= com_googlecode_phpreader_Transform::toString16BE($this->_text);
      break;
    case self::UTF16LE:
      $data .= com_googlecode_phpreader_Transform::toString16LE($this->_text);
      break;
    default:
      $data .= $this->_text;
    }
    $this->setData($data);
    return parent::__toString();
  }
}
