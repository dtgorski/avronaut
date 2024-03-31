<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Parse;

// @formatter:off
/** @internal */
class Lexer
{
    private const SCANNING = 0xFF;
    private const states = [
        0,              //     [0x00] invalid
        0,              //     [0x01] invalid
        0,              //     [0x02] invalid
        0,              //     [0x03] invalid
        0,              //     [0x04] invalid
        0,              //     [0x05] invalid
        0,              //     [0x06] invalid
        0,              //     [0x07] invalid
        0,              //     [0x08] invalid
        0,              //     [0x09] \t
        0,              //     [0x0A] \n
        0,              //     [0x0B] invalid
        0,              //     [0x0C] \f
        0,              //     [0x0D] \r
        0,              //     [0x0E] invalid
        0,              //     [0x0F] invalid
        0,              //     [0x10] invalid
        0,              //     [0x11] invalid
        0,              //     [0x12] invalid
        0,              //     [0x13] invalid
        0,              //     [0x14] invalid
        0,              //     [0x15] invalid
        0,              //     [0x16] invalid
        0,              //     [0x17] invalid
        0,              //     [0x18] invalid
        0,              //     [0x19] invalid
        0,              //     [0x1A] invalid
        0,              //     [0x1B] invalid
        0,              //     [0x1C] invalid
        0,              //     [0x1D] invalid
        0,              //     [0x1E] invalid
        0,              //     [0x1F] invalid
        0,              //     [0x20] space
        0,              //  !  [0x21] exclamation mark
        Token::STRING,  //  "  [0x22] quotation mark
        0,              //  #  [0x23] number sign
        Token::IDENT,   //  $  [0x24] dollar sign
        0,              //  %  [0x25] percent sign
        0,              //  &  [0x26] ampersand
        0,              //  \  [0x27] apostrophe
        Token::LPAREN,  //  (  [0x28] left parenthesis
        Token::RPAREN,  //  )  [0x29] right parenthesis
        0,              //  *  [0x2A] asterisk
        Token::NUMBER,  //  +  [0x2B] plus sign
        Token::COMMA,   //  ,  [0x2C] comma
        Token::DASH,    //  -  [0x2D] dash / minus sign
        Token::DOT,     //  .  [0x2E] full stop
        Token::SLASH,   //  /  [0x2F] forward slash
        Token::NUMBER,  //  0  [0x30] digit
        Token::NUMBER,  //  1  [0x31] digit
        Token::NUMBER,  //  2  [0x32] digit
        Token::NUMBER,  //  3  [0x33] digit
        Token::NUMBER,  //  4  [0x34] digit
        Token::NUMBER,  //  5  [0x35] digit
        Token::NUMBER,  //  6  [0x36] digit
        Token::NUMBER,  //  7  [0x37] digit
        Token::NUMBER,  //  8  [0x38] digit
        Token::NUMBER,  //  9  [0x39] digit
        Token::COLON,   //  :  [0x3A] colon
        Token::SEMICOL, //  ;  [0x3B] semicolon
        Token::LT,      //  <  [0x3C] less-than sign
        Token::EQ,      //  =  [0x3D] equals sign
        Token::GT,      //  >  [0x3E] greater-than sign
        Token::QMARK,   //  ?  [0x3F] question mark
        Token::AT,      //  @  [0x40] commercial at
        Token::IDENT,   //  A  [0x41] capital letter
        Token::IDENT,   //  B  [0x42] capital letter
        Token::IDENT,   //  C  [0x43] capital letter
        Token::IDENT,   //  D  [0x44] capital letter
        Token::IDENT,   //  E  [0x45] capital letter
        Token::IDENT,   //  F  [0x46] capital letter
        Token::IDENT,   //  G  [0x47] capital letter
        Token::IDENT,   //  H  [0x48] capital letter
        Token::IDENT,   //  I  [0x49] capital letter
        Token::IDENT,   //  J  [0x4A] capital letter
        Token::IDENT,   //  K  [0x4B] capital letter
        Token::IDENT,   //  L  [0x4C] capital letter
        Token::IDENT,   //  M  [0x4D] capital letter
        Token::IDENT,   //  N  [0x4E] capital letter
        Token::IDENT,   //  O  [0x4F] capital letter
        Token::IDENT,   //  P  [0x50] capital letter
        Token::IDENT,   //  Q  [0x51] capital letter
        Token::IDENT,   //  R  [0x52] capital letter
        Token::IDENT,   //  S  [0x53] capital letter
        Token::IDENT,   //  T  [0x54] capital letter
        Token::IDENT,   //  U  [0x55] capital letter
        Token::IDENT,   //  V  [0x56] capital letter
        Token::IDENT,   //  W  [0x57] capital letter
        Token::IDENT,   //  X  [0x58] capital letter
        Token::IDENT,   //  Y  [0x59] capital letter
        Token::IDENT,   //  Z  [0x5A] capital letter
        Token::LBRACK,  //  [  [0x5B] left square bracket
        0,              //  \  [0x5C] reverse slash
        Token::RBRACK,  //  ]  [0x5D] right square bracket
        0,              //  ^  [0x5E] circumflex accent
        Token::IDENT,   //  _  [0x5F] low line
        Token::TICK,    //  `  [0x60] grave accent
        Token::IDENT,   //  a  [0x61] small letter
        Token::IDENT,   //  b  [0x62] small letter
        Token::IDENT,   //  c  [0x63] small letter
        Token::IDENT,   //  d  [0x64] small letter
        Token::IDENT,   //  e  [0x65] small letter
        Token::IDENT,   //  f  [0x66] small letter
        Token::IDENT,   //  g  [0x67] small letter
        Token::IDENT,   //  h  [0x68] small letter
        Token::IDENT,   //  i  [0x69] small letter
        Token::IDENT,   //  j  [0x6A] small letter
        Token::IDENT,   //  k  [0x6B] small letter
        Token::IDENT,   //  l  [0x6C] small letter
        Token::IDENT,   //  m  [0x6D] small letter
        Token::IDENT,   //  n  [0x6E] small letter
        Token::IDENT,   //  o  [0x6F] small letter
        Token::IDENT,   //  p  [0x70] small letter
        Token::IDENT,   //  q  [0x71] small letter
        Token::IDENT,   //  r  [0x72] small letter
        Token::IDENT,   //  s  [0x73] small letter
        Token::IDENT,   //  t  [0x74] small letter
        Token::IDENT,   //  u  [0x75] small letter
        Token::IDENT,   //  v  [0x76] small letter
        Token::IDENT,   //  w  [0x77] small letter
        Token::IDENT,   //  x  [0x78] small letter
        Token::IDENT,   //  y  [0x79] small letter
        Token::IDENT,   //  z  [0x7A] small letter
        Token::LBRACE,  //  {  [0x7B] left curly brace
        0,              //  |  [0x7C] vertical line
        Token::RBRACE,  //  }  [0x7D] right curly brace
        0,              //  ~  [0x7E] tilde
        0,              //     [0x7F] invalid
    ];

    public function createTokenStream(ByteReader $reader): \Generator  {
        $byte = 0;
        $prev = 0;
        $last = 0;
        $hold = false;

        nextToken:
            $state = self::SCANNING;

            $esc = false;
            $frac = false;
            $load = [];

            $line = $reader->getLine();
            $col  = $reader->getColumn();

        nextByte:
            if ($hold) {
                $hold = false;
            } else {
                $prev = $last;
                $last = $byte;
                $byte = $reader->readByte();
            }

            if ($byte === 0x00) {
                if ($state === Token::IDENT)   { goto holdByteEmitToken; }
                if ($state === Token::NUMBER ) { goto holdByteEmitNumber; }
                if ($state === Token::EXPO)    { goto holdByteEmitNumber; }
                if ($state === Token::SIGN)    { goto emitError; }
                if (sizeof($load) === 0)   { goto emitEOF; }
                goto emitToken;
            }

            if ($state !== self::SCANNING) {
                switch ($state) {
                    case Token::IDENT:   goto scanIdentifier;
                    case Token::STRING:  goto scanString;
                    case Token::DASH:    goto scanAfterDash;
                    case Token::DOT:     goto scanAfterDot;
                    case Token::NUMBER:  goto scanNumber;
                    case Token::SIGN:    goto scanExpoSign;
                    case Token::EXPO:    goto scanExpo;
                    case Token::SLASH:   goto scanAfterSlash;
                    case Token::COMBLCK: goto scanCommentBlock;
                    case Token::COMLINE: goto scanCommentLine;
                }
                goto holdByteEmitToken;
            }

            if ($byte === 0x20 || $byte === 0x09) { goto nextByte; }
            if ($byte === 0x0A || $byte === 0x0D) { goto nextByte; }
            if ($byte === 0x0C) { goto nextByte; }

            if ($byte <= 0x1F || $byte >= 0x80) { goto emitError; }

            $state = self::states[$byte];
            $line = $reader->getLine();
            $col  = $reader->getColumn();

            if ($state === 0x00) { goto emitError; }
            if ($byte === 0x22) { goto nextByte; }
            goto acceptByte;

        scanString:
            if ($esc) { $esc = !$esc; goto acceptByte; }
            if ($byte === 0x5C) { $esc = true; goto nextByte; }
            if ($byte !== 0x22) { goto acceptByte; }
            goto emitToken;

        scanAfterSlash:
            if ($byte === 0x2F) { $state = Token::COMLINE; goto acceptByte; }
            if ($byte === 0x2A) { $state = Token::COMBLCK; goto acceptByte; }
            goto holdByteEmitToken;

        scanCommentBlock:
            if (sizeof($load) <= 2) { goto acceptByte; }
            if ($byte !== 0x2F)     { goto acceptByte; }
            if ($last !== 0x2A)     { goto acceptByte; }
            goto acceptByteEmitToken;

        scanCommentLine:
            if ($byte !== 0x0A) { goto acceptByte; }
            goto holdByteEmitToken;

        scanAfterDash:
            if ($byte >= 0x30 && $byte <= 0x39) { $state = Token::NUMBER; goto acceptByte;  }
            if ($byte === 0x2E) { $frac = true; $state = Token::NUMBER; goto acceptByte; }
            goto holdByteEmitToken;

        scanAfterDot:
            if ($byte >= 0x30 && $byte <= 0x39) { $frac = true; $state = Token::NUMBER; goto acceptByte; }
            goto holdByteEmitToken;

        scanExpoSign:
            $state = Token::EXPO;
            if ($byte === 0x2B || $byte === 0x2D) { goto acceptByte; }

        scanExpo:
            if ($byte >= 0x30 && $byte <= 0x39) { goto acceptByte; }
            goto holdByteEmitNumber;

        scanNumber:
            if ($byte >= 0x30 && $byte <= 0x39) { goto acceptByte; }
            if (!$frac && $byte === 0x2E) { $frac = true; goto acceptByte; }

            if (($byte === 0x45 || $byte === 0x65) && ($last >= 0x30 && $last <= 0x39)) {
                $state = Token::SIGN; goto acceptByte;
            }

        holdByteEmitNumber:
            if ($last === 0x2E) {
                if ($prev === 0x45 || $prev === 0x65) { goto emitError; }
                if ($prev === 0x2D || $prev === 0x2B) { goto emitError; }
            }
            if ($last === 0x45 || $last === 0x65) { goto emitError; }
            if ($last === 0x2B || $last === 0x2D) { goto emitError; }
            $state = Token::NUMBER;
            goto holdByteEmitToken;

        scanIdentifier:
            if ($byte >= 0x41 && $byte <= 0x5A) { goto acceptByte; }
            if ($byte >= 0x61 && $byte <= 0x7A) { goto acceptByte; }
            if ($byte >= 0x30 && $byte <= 0x39) { goto acceptByte; }
            if ($byte == 0x24 || $byte == 0x5F) { goto acceptByte; }

        holdByteEmitToken:
            $hold = true;

        emitToken:
            yield new Token($state, pack('c*', ...$load), $line, $col);
            goto nextToken;

        emitError:
            $msg = sprintf("unexpected '%s' (0x%X)", chr($byte), $byte);
            $msg = $byte === 0x00 ? 'unexpected end of input' : $msg;
            yield new Token(Token::ERR, $msg, $line, $col);

        emitEOF:
            yield new Token(Token::EOF, '', $line, $col);
            return;

        acceptByteEmitToken:
            $load[] = $byte;
            goto emitToken;

        acceptByte:
            $load[] = $byte;
            goto nextByte;
    }
}
