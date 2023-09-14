<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Alias\{ArrayPushFixer,
    BacktickToShellExecFixer,
    EregToPregFixer,
    MbStrFunctionsFixer,
    NoAliasFunctionsFixer,
    NoAliasLanguageConstructCallFixer,
    NoMixedEchoPrintFixer,
    PowToExponentiationFixer,
    RandomApiMigrationFixer,
    SetTypeToCastFixer
};
use PhpCsFixer\Fixer\ArrayNotation\{NoMultilineWhitespaceAroundDoubleArrowFixer,
    NormalizeIndexBraceFixer,
    NoWhitespaceBeforeCommaInArrayFixer,
    TrimArraySpacesFixer,
    WhitespaceAfterCommaInArrayFixer
};
use PhpCsFixer\Fixer\Basic\{EncodingFixer, NonPrintableCharacterFixer, PsrAutoloadingFixer};
use PhpCsFixer\Fixer\Casing\{ConstantCaseFixer,
    LowercaseKeywordsFixer,
    LowercaseStaticReferenceFixer,
    MagicConstantCasingFixer,
    MagicMethodCasingFixer,
    NativeFunctionCasingFixer,
    NativeFunctionTypeDeclarationCasingFixer
};
use PhpCsFixer\Fixer\CastNotation\{CastSpacesFixer,
    LowercaseCastFixer,
    ModernizeTypesCastingFixer,
    NoShortBoolCastFixer,
    NoUnsetCastFixer,
    ShortScalarCastFixer
};
use PhpCsFixer\Fixer\ClassNotation\{ClassDefinitionFixer,
    FinalInternalClassFixer,
    NoBlankLinesAfterClassOpeningFixer,
    NoNullPropertyInitializationFixer,
    NoPhp4ConstructorFixer,
    NoUnneededFinalMethodFixer,
    OrderedClassElementsFixer,
    OrderedInterfacesFixer,
    OrderedTraitsFixer,
    ProtectedToPrivateFixer,
    SelfAccessorFixer,
    SelfStaticAccessorFixer,
    SingleClassElementPerStatementFixer,
    SingleTraitInsertPerStatementFixer,
    VisibilityRequiredFixer
};
use PhpCsFixer\Fixer\ClassUsage\DateTimeImmutableFixer;
use PhpCsFixer\Fixer\Comment\{CommentToPhpdocFixer,
    MultilineCommentOpeningClosingFixer,
    NoEmptyCommentFixer,
    NoTrailingWhitespaceInCommentFixer,
    SingleLineCommentStyleFixer
};
use PhpCsFixer\Fixer\ControlStructure\{ElseifFixer,
    IncludeFixer,
    NoAlternativeSyntaxFixer,
    NoBreakCommentFixer,
    NoSuperfluousElseifFixer,
    NoUnneededControlParenthesesFixer,
    NoUnneededCurlyBracesFixer,
    NoUselessElseFixer,
    SimplifiedIfReturnFixer,
    SwitchCaseSemicolonToColonFixer,
    SwitchCaseSpaceFixer,
    SwitchContinueToBreakFixer,
    TrailingCommaInMultilineFixer,
    YodaStyleFixer
};
use PhpCsFixer\Fixer\FunctionNotation\{CombineNestedDirnameFixer,
    FopenFlagOrderFixer,
    FopenFlagsFixer,
    FunctionDeclarationFixer,
    FunctionTypehintSpaceFixer,
    ImplodeCallFixer,
    LambdaNotUsedImportFixer,
    MethodArgumentSpaceFixer,
    NoUnreachableDefaultArgumentValueFixer,
    NoUselessSprintfFixer,
    NullableTypeDeclarationForDefaultNullValueFixer,
    RegularCallableCallFixer,
    ReturnTypeDeclarationFixer,
    SingleLineThrowFixer,
    UseArrowFunctionsFixer,
    VoidReturnFixer
};
use PhpCsFixer\Fixer\Import\{FullyQualifiedStrictTypesFixer,
    GroupImportFixer,
    NoLeadingImportSlashFixer,
    NoUnusedImportsFixer,
    OrderedImportsFixer,
    SingleLineAfterImportsFixer
};
use PhpCsFixer\Fixer\LanguageConstruct\{CombineConsecutiveIssetsFixer,
    CombineConsecutiveUnsetsFixer,
    DeclareEqualNormalizeFixer,
    DirConstantFixer,
    ErrorSuppressionFixer,
    ExplicitIndirectVariableFixer,
    FunctionToConstantFixer,
    IsNullFixer,
    NoUnsetOnPropertyFixer
};
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use PhpCsFixer\Fixer\NamespaceNotation\{BlankLineAfterNamespaceFixer,
    CleanNamespaceFixer,
    NoLeadingNamespaceWhitespaceFixer
};
use PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer;
use PhpCsFixer\Fixer\Operator\{BinaryOperatorSpacesFixer,
    ConcatSpaceFixer,
    IncrementStyleFixer,
    LogicalOperatorsFixer,
    NewWithBracesFixer,
    ObjectOperatorWithoutWhitespaceFixer,
    OperatorLinebreakFixer,
    StandardizeIncrementFixer,
    StandardizeNotEqualsFixer,
    TernaryOperatorSpacesFixer,
    TernaryToElvisOperatorFixer,
    TernaryToNullCoalescingFixer,
    UnaryOperatorSpacesFixer
};
use PhpCsFixer\Fixer\Phpdoc\{GeneralPhpdocAnnotationRemoveFixer,
    GeneralPhpdocTagRenameFixer,
    NoBlankLinesAfterPhpdocFixer,
    NoEmptyPhpdocFixer,
    NoSuperfluousPhpdocTagsFixer,
    PhpdocAddMissingParamAnnotationFixer,
    PhpdocAlignFixer,
    PhpdocAnnotationWithoutDotFixer,
    PhpdocIndentFixer,
    PhpdocInlineTagNormalizerFixer,
    PhpdocLineSpanFixer,
    PhpdocNoAccessFixer,
    PhpdocNoAliasTagFixer,
    PhpdocNoEmptyReturnFixer,
    PhpdocNoPackageFixer,
    PhpdocNoUselessInheritdocFixer,
    PhpdocOrderByValueFixer,
    PhpdocOrderFixer,
    PhpdocReturnSelfReferenceFixer,
    PhpdocScalarFixer,
    PhpdocSeparationFixer,
    PhpdocSingleLineVarSpacingFixer,
    PhpdocSummaryFixer,
    PhpdocTagCasingFixer,
    PhpdocTagTypeFixer,
    PhpdocTrimConsecutiveBlankLineSeparationFixer,
    PhpdocTrimFixer,
    PhpdocTypesFixer,
    PhpdocTypesOrderFixer,
    PhpdocVarAnnotationCorrectOrderFixer,
    PhpdocVarWithoutNameFixer
};
use PhpCsFixer\Fixer\PhpTag\{BlankLineAfterOpeningTagFixer,
    EchoTagSyntaxFixer,
    FullOpeningTagFixer,
    LinebreakAfterOpeningTagFixer,
    NoClosingTagFixer
};
use PhpCsFixer\Fixer\PhpUnit\{PhpUnitConstructFixer,
    PhpUnitDedicateAssertFixer,
    PhpUnitDedicateAssertInternalTypeFixer,
    PhpUnitFqcnAnnotationFixer,
    PhpUnitInternalClassFixer,
    PhpUnitMethodCasingFixer,
    PhpUnitMockFixer,
    PhpUnitMockShortWillReturnFixer,
    PhpUnitNamespacedFixer,
    PhpUnitNoExpectationAnnotationFixer,
    PhpUnitSetUpTearDownVisibilityFixer,
    PhpUnitSizeClassFixer,
    PhpUnitStrictFixer,
    PhpUnitTestAnnotationFixer,
    PhpUnitTestCaseStaticMethodCallsFixer
};
use PhpCsFixer\Fixer\ReturnNotation\{NoUselessReturnFixer, SimplifiedNullReturnFixer};
use PhpCsFixer\Fixer\Semicolon\{MultilineWhitespaceBeforeSemicolonsFixer,
    NoEmptyStatementFixer,
    NoSinglelineWhitespaceBeforeSemicolonsFixer,
    SemicolonAfterInstructionFixer,
    SpaceAfterSemicolonFixer
};
use PhpCsFixer\Fixer\Strict\{DeclareStrictTypesFixer, StrictComparisonFixer, StrictParamFixer};
use PhpCsFixer\Fixer\StringNotation\{EscapeImplicitBackslashesFixer,
    ExplicitStringVariableFixer,
    HeredocToNowdocFixer,
    NoBinaryStringFixer,
    NoTrailingWhitespaceInStringFixer,
    SimpleToComplexStringVariableFixer,
    SingleQuoteFixer,
    StringLineEndingFixer
};
use PhpCsFixer\Fixer\Whitespace\{ArrayIndentationFixer,
    BlankLineBeforeStatementFixer,
    CompactNullableTypehintFixer,
    HeredocIndentationFixer,
    IndentationTypeFixer,
    LineEndingFixer,
    MethodChainingIndentationFixer,
    NoExtraBlankLinesFixer,
    NoSpacesAroundOffsetFixer,
    NoSpacesInsideParenthesisFixer,
    NoTrailingWhitespaceFixer,
    NoWhitespaceInBlankLineFixer,
    SingleBlankLineAtEofFixer
};
use SlevomatCodingStandard\Sniffs\ControlStructures\RequireShortTernaryOperatorSniff;
use SlevomatCodingStandard\Sniffs\Functions\UnusedInheritedVariablePassedToClosureSniff;
use SlevomatCodingStandard\Sniffs\Operators\RequireCombinedAssignmentOperatorSniff;
use SlevomatCodingStandard\Sniffs\PHP\{DisallowDirectMagicInvokeCallSniff, UselessSemicolonSniff};
use SlevomatCodingStandard\Sniffs\Variables\UselessVariableSniff;
use Symplify\CodingStandard\Fixer\Annotation\RemovePHPStormAnnotationFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\{ArrayOpenerAndCloserNewlineFixer, StandaloneLineInMultilineArrayFixer};
use Symplify\CodingStandard\Fixer\Commenting\{ParamReturnAndVarTagMalformsFixer, RemoveUselessDefaultCommentFixer};
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\{SpaceAfterCommaHereNowDocFixer, StandaloneLinePromotedPropertyFixer};
use Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/lang',
        __DIR__ . '/database',
    ]);

    $parameters = $ecsConfig->parameters();

    $parameters->set(Option::PARALLEL, true);

//    $ecsConfig->sets([SetList::LARAVEL]);

    // PhpCsFixer\Fixer\Alias
    $ecsConfig->rule(ArrayPushFixer::class); // Converts simple usages of `array_push($x, $y);` to `$x[] = $y;`.
    $ecsConfig->rule(BacktickToShellExecFixer::class); // Converts backtick execution operators to `shell_exec` calls.
    $ecsConfig->rule(EregToPregFixer::class); // Replace deprecated `ereg` regular expression functions with `preg`.
    $ecsConfig->rule(MbStrFunctionsFixer::class); // Replace non multibyte-safe functions with corresponding mb function.
    $ecsConfig->rule(NoAliasFunctionsFixer::class); // Use master functions instead of aliases.
    $ecsConfig->rule(NoAliasLanguageConstructCallFixer::class); // Use aster language constructs instead of aliases.
    $ecsConfig->rule(NoMixedEchoPrintFixer::class); // Either language construct `print` or `echo` should be used, not mixed.
    $ecsConfig->rule(PowToExponentiationFixer::class); // Converts `pow` to the `**` operator.
    $ecsConfig->rule(RandomApiMigrationFixer::class); // Replaces `rand`, `srand`, `getrandmax` functions calls with their `mt_*` analogs.
    $ecsConfig->rule(SetTypeToCastFixer::class); // Use cast instead of `settype`.

    // PhpCsFixer\Fixer\ArrayNotation
    $ecsConfig->rule(NoMultilineWhitespaceAroundDoubleArrowFixer::class); // Operator `=>` should not be surrounded by multi-line whitespaces.
    $ecsConfig->rule(NormalizeIndexBraceFixer::class); // Array index should always be written by using square braces.
    $ecsConfig->rule(NoWhitespaceBeforeCommaInArrayFixer::class); // In array declaration, there MUST NOT be a whitespace before each comma.
    $ecsConfig->rule(TrimArraySpacesFixer::class); // Arrays should be formatted like function/method arguments, without leading or trailing single line space.
    $ecsConfig->rule(WhitespaceAfterCommaInArrayFixer::class); // In array declaration, there must be a whitespace after each comma.

    // PhpCsFixer\Fixer\Basic
    $ecsConfig->rule(EncodingFixer::class); // PHP code MUST use only UTF-8 without BOM (remove BOM).
    $ecsConfig->rule(NonPrintableCharacterFixer::class); // Remove Zero-width space (ZWSP), Non-breaking space (NBSP) and other invisible unicode symbols.
    $ecsConfig->rule(PsrAutoloadingFixer::class); // Classes must be in a path that matches their namespace, be at least one namespace deep and the class name should match the file name.

    // PhpCsFixer\Fixer\Casing
    $ecsConfig->rule(ConstantCaseFixer::class); // The PHP constants `true`, `false`, and `null` must be written using the correct casing (lower).
    $ecsConfig->rule(LowercaseKeywordsFixer::class); // PHP keywords MUST be in lower case.
    $ecsConfig->rule(LowercaseStaticReferenceFixer::class); // Class static references `self`, `static` and `parent` MUST be in lower case.
    $ecsConfig->rule(MagicConstantCasingFixer::class); // Magic constants should be referred to using the correct casing.
    $ecsConfig->rule(MagicMethodCasingFixer::class); // Magic method definitions and calls must be using the correct casing.
    $ecsConfig->rule(NativeFunctionCasingFixer::class); // Function defined by PHP should be called using the correct casing.
    $ecsConfig->rule(NativeFunctionTypeDeclarationCasingFixer::class); // Native type hints for functions should use the correct case.

    // PhpCsFixer\Fixer\CastNotation
    $ecsConfig->rule(CastSpacesFixer::class); // A single space or none should be between cast and variable.
    $ecsConfig->rule(LowercaseCastFixer::class); // Casts should be written in lower case.
    $ecsConfig->rule(ModernizeTypesCastingFixer::class); // Replace `intval`, `floatval`, `doubleval`, `strval` and `boolval` function calls with according type casting operator.
    $ecsConfig->rule(NoShortBoolCastFixer::class); // Short cast `bool` using double exclamation mark should not be used.
    $ecsConfig->rule(NoUnsetCastFixer::class); // Variables must be set `null` instead of using `(unset)` casting.
    $ecsConfig->rule(ShortScalarCastFixer::class); // Cast `(boolean)` and `(integer)` should be written as `(bool)` and `(int)`, `(double)` and `(real)` as `(float)`, `(binary)` as `(string)`.

    // PhpCsFixer\Fixer\ClassNotation
    $ecsConfig->rule(SingleTraitInsertPerStatementFixer::class); // A single Trait use per line
    $ecsConfig->ruleWithConfiguration(ClassDefinitionFixer::class,
        ['single_line' => true]); // Whitespace around the keywords of a class, trait or interfaces definition should be one space.

    $ecsConfig->rule(FinalInternalClassFixer::class); // All internal classes should be `final`.
    $ecsConfig->rule(NoBlankLinesAfterClassOpeningFixer::class); // There should be no empty lines after class opening brace.
    $ecsConfig->rule(NoNullPropertyInitializationFixer::class); // Properties must not be explicitly initialized with `null` except when they have a type declaration (PHP 7.4).
    $ecsConfig->rule(NoPhp4ConstructorFixer::class); // Convert PHP4-style constructors to `__construct`.
    $ecsConfig->rule(NoUnneededFinalMethodFixer::class); // A `final` class must not have `final` methods and `private` methods must not be `final`.
    $ecsConfig->rule(OrderedClassElementsFixer::class); // Orders the elements of classes/interfaces/traits.
    $ecsConfig->rule(OrderedInterfacesFixer::class); // Orders the interfaces in an `implements` or `interface extends` clause.
    $ecsConfig->rule(OrderedTraitsFixer::class); // Trait `use` statements must be sorted alphabetically.
    $ecsConfig->rule(ProtectedToPrivateFixer::class); // Converts `protected` variables and methods to `private` where possible.
    $ecsConfig->rule(SelfAccessorFixer::class); // Inside class or interface element `self` should be preferred to the class name itself.
    $ecsConfig->rule(SelfStaticAccessorFixer::class); // Inside a `final` class or anonymous class `self` should be preferred to `static`.
    $ecsConfig->rule(SingleClassElementPerStatementFixer::class); // There must not be more than one property or constant declared per statement.
    $ecsConfig->rule(VisibilityRequiredFixer::class); // Visibility must be declared on all properties and methods; `abstract` and `final` must be declared before the visibility; `static` must be declared after the visibility.

    // PhpCsFixer\Fixer\ClassUsage
    $ecsConfig->rule(DateTimeImmutableFixer::class); // Class `DateTimeImmutable` should always be used instead of `DateTime`.

    // PhpCsFixer\Fixer\Comment
    $ecsConfig->rule(CommentToPhpdocFixer::class); // Comments with annotation should be docblock when used on structural elements.
    $ecsConfig->rule(MultilineCommentOpeningClosingFixer::class); // DocBlocks must start with two asterisks, multiline comments must start with a single asterisk, after the opening slash. Both must end with a single asterisk before the closing slash.
    $ecsConfig->rule(NoEmptyCommentFixer::class); // There should not be any empty comments.
    $ecsConfig->rule(NoTrailingWhitespaceInCommentFixer::class); // There must be no trailing spaces inside comment or PHPDoc.
    $ecsConfig->rule(SingleLineCommentStyleFixer::class); // Single-line comments and multi-line comments with only one line of actual content should use the `//` syntax.

    // PhpCsFixer\Fixer\ControlStructure
    $ecsConfig->rule(ElseifFixer::class); // The keyword `elseif` should be used instead of `else if` so that all control keywords look like single words.
    $ecsConfig->rule(IncludeFixer::class); // Include/Require and file path should be divided with a single space. File path should not be placed under brackets.
    $ecsConfig->rule(NoAlternativeSyntaxFixer::class); // Replace control structure alternative syntax to use braces
    $ecsConfig->rule(NoBreakCommentFixer::class); // There must be a comment when fall-through is intentional in a non-empty case body.
    $ecsConfig->rule(NoSuperfluousElseifFixer::class); // Replaces superfluous `elseif` with `if`.
    $ecsConfig->rule(NoUnneededControlParenthesesFixer::class); // Removes unneeded parentheses around control statements.
    $ecsConfig->ruleWithConfiguration(NoUnneededCurlyBracesFixer::class,
        ['namespaces' => true]); // Removes unneeded curly braces that are superfluous and aren\'t part of a control structure\'s body.
    $ecsConfig->rule(NoUselessElseFixer::class); // There should not be useless `else` cases.
    $ecsConfig->rule(SimplifiedIfReturnFixer::class); // Simplify `if` control structures that return the boolean result of their condition.
    $ecsConfig->rule(SwitchCaseSemicolonToColonFixer::class); // A case should be followed by a colon and not a semicolon.
    $ecsConfig->rule(SwitchCaseSpaceFixer::class); // Removes extra spaces between colon and case value.
    $ecsConfig->rule(SwitchContinueToBreakFixer::class); // Switch case must not be ended with `continue` but with `break`.
    $ecsConfig->ruleWithConfiguration(TrailingCommaInMultilineFixer::class,
        ['elements' => ['arrays']]); // Multi-line arrays, arguments list and parameters list must have a trailing comma.

    $ecsConfig->ruleWithConfiguration(YodaStyleFixer::class,
        ['equal' => false, 'identical' => false, 'less_and_greater' => false]); // Write conditions in non-Yoda style.

    // PhpCsFixer\Fixer\FunctionNotation
    $ecsConfig->rule(CombineNestedDirnameFixer::class); // Replace multiple nested calls of `dirname` by only one call with second `$level` parameter. Requires PHP >= 7.0.
    $ecsConfig->rule(FopenFlagOrderFixer::class); // Order the flags in `fopen` calls, `b` and `t` must be last.
    $ecsConfig->rule(FopenFlagsFixer::class); // The flags in `fopen` calls must omit `t`, and `b` must be omitted or included consistently.
    $ecsConfig->rule(FunctionDeclarationFixer::class); // Spaces should be properly placed in a function declaration.
    $ecsConfig->rule(FunctionTypehintSpaceFixer::class); // Ensure single space between function\'s argument and its typehint.
    $ecsConfig->rule(ImplodeCallFixer::class); // Function `implode` must be called with 2 arguments in the documented order.
    $ecsConfig->rule(LambdaNotUsedImportFixer::class); // Lambda must not import variables it doesn't use.
    $ecsConfig->rule(MethodArgumentSpaceFixer::class); // In method arguments and method call, there must not be a space before each comma and there must be one space after each comma. Argument lists may be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list must be on the next line, and there must be only one argument per line.
//        $ecsConfig->rule(NativeFunctionInvocationFixer::class) // Add leading `\` before function invocation to speed up resolving.
    $ecsConfig->rule(NoUnreachableDefaultArgumentValueFixer::class); // In function arguments there must not be arguments with default values before non-default ones.
    $ecsConfig->rule(NoUselessSprintfFixer::class); // There must be no `sprintf` calls with only the first argument.
    $ecsConfig->rule(NullableTypeDeclarationForDefaultNullValueFixer::class); // Removes `?` before type declarations for parameters with a default `null` value.
    $ecsConfig->rule(RegularCallableCallFixer::class); // Callables must be called without using `call_user_func*` when possible.
    $ecsConfig->rule(ReturnTypeDeclarationFixer::class); // There should be one or no space before colon, and one space after it in return type declarations, according to configuration.
    $ecsConfig->rule(SingleLineThrowFixer::class); // Throwing exception must be done in single line.
    $ecsConfig->rule(UseArrowFunctionsFixer::class); // Anonymous functions with one-liner return statement must use arrow functions.
    $ecsConfig->rule(VoidReturnFixer::class); // Add `void` return type to functions with missing or empty return statements, but priority is given to `@return` annotations. Requires PHP >= 7.1.

    // PhpCsFixer\Fixer\Import
    $ecsConfig->rule(FullyQualifiedStrictTypesFixer::class); // Transforms imported FQCN parameters and return types in function arguments to short version.
//        $ecsConfig->rule(GlobalNamespaceImportFixer::class) // Imports or fully qualifies global classes/functions/constants.
    $ecsConfig->rule(GroupImportFixer::class); // There must be groupd usage for the same namespaces.
    $ecsConfig->rule(NoLeadingImportSlashFixer::class); // Remove leading slashes in `use` clauses.
    $ecsConfig->rule(NoUnusedImportsFixer::class); // Unused `use` statements must be removed.
    $ecsConfig->rule(OrderedImportsFixer::class); // Order `use` statements.
    $ecsConfig->rule(SingleLineAfterImportsFixer::class); // Each namespace use must go on its own line and there must be one blank line after the use statements block.

    // PhpCsFixer\Fixer\LanguageConstruct
    $ecsConfig->rule(CombineConsecutiveIssetsFixer::class); // Using `isset($var) &&` multiple times should be done in one call.
    $ecsConfig->rule(CombineConsecutiveUnsetsFixer::class); // Calling `unset` on multiple items should be done in one call.
    $ecsConfig->rule(DeclareEqualNormalizeFixer::class); // Equal sign in declare statement should be surrounded by spaces or not following configuration.
    $ecsConfig->rule(DirConstantFixer::class); // Replaces `dirname(__FILE__)` expression with equivalent `__DIR__` constant.
    $ecsConfig->rule(ErrorSuppressionFixer::class); // Error control operator should be added to deprecation notices and/or removed from other cases.
    $ecsConfig->rule(ExplicitIndirectVariableFixer::class); // Add curly braces to indirect variables to make them clear to understand. Requires PHP >= 7.0.
    $ecsConfig->rule(FunctionToConstantFixer::class); // Replace core functions calls returning constants with the constants.
    $ecsConfig->rule(IsNullFixer::class); // Replaces `is_null($var)` expression with `null === $var`.
    $ecsConfig->rule(NoUnsetOnPropertyFixer::class); // Properties should be set to `null` instead of using `unset`.

    // PhpCsFixer\Fixer\ListNotation
    $ecsConfig->rule(ListSyntaxFixer::class); // List (`array` destructuring) assignment should be declared using the configured syntax. Requires PHP >= 7.1.

    // PhpCsFixer\Fixer\NamespaceNotation
    $ecsConfig->rule(BlankLineAfterNamespaceFixer::class); // There must be one blank line after the namespace declaration.
    $ecsConfig->rule(CleanNamespaceFixer::class); // Namespace must not contain spacing, comments or PHPDoc.
    $ecsConfig->rule(NoLeadingNamespaceWhitespaceFixer::class); // The namespace declaration line shouldn't contain leading whitespace.

    // PhpCsFixer\Fixer\Naming
    $ecsConfig->rule(NoHomoglyphNamesFixer::class); // Replace accidental usage of homoglyphs (non ascii characters) in names.

    // PhpCsFixer\Fixer\Operator
    $ecsConfig->ruleWithConfiguration(BinaryOperatorSpacesFixer::class,
        ['operators' => ['|' => 'no_space']]); // Binary operators should be surrounded by space as configured.

    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class,
        ['spacing' => 'one']); // Concatenation should be spaced according configuration.
    $ecsConfig->rule(IncrementStyleFixer::class); // Pre- or post-increment and decrement operators should be used if possible.
    $ecsConfig->rule(LogicalOperatorsFixer::class); // Use `&&` and `||` logical operators instead of `and` and `or`.
    $ecsConfig->rule(NewWithBracesFixer::class); // All instances created with new keyword must be followed by braces.
    $ecsConfig->rule(ObjectOperatorWithoutWhitespaceFixer::class); // There should not be space before or after object operators `->` and `?->`.
    $ecsConfig->rule(OperatorLinebreakFixer::class); // Operators - when multiline - must always be at the beginning or at the end of the line.
    $ecsConfig->rule(StandardizeIncrementFixer::class); // Increment and decrement operators should be used if possible.
    $ecsConfig->rule(StandardizeNotEqualsFixer::class); // Replace all `<>` with `!=`.
    $ecsConfig->rule(TernaryOperatorSpacesFixer::class); // Standardize spaces around ternary operator.
    $ecsConfig->rule(TernaryToElvisOperatorFixer::class); // Use the Elvis operator `?:` where possible.
    $ecsConfig->rule(TernaryToNullCoalescingFixer::class); // Use `null` coalescing operator `??` where possible. Requires PHP >= 7.0.
    $ecsConfig->rule(UnaryOperatorSpacesFixer::class); // Unary operators should be placed adjacent to their operands.

    // PhpCsFixer\Fixer\Phpdoc
    $ecsConfig->rule(GeneralPhpdocAnnotationRemoveFixer::class); // Configured annotations should be omitted from PHPDoc.
    $ecsConfig->rule(GeneralPhpdocTagRenameFixer::class); // Renames PHPDoc tags.
    $ecsConfig->rule(NoBlankLinesAfterPhpdocFixer::class); // There should not be blank lines between docblock and the documented element.
    $ecsConfig->rule(NoEmptyPhpdocFixer::class); // There should not be empty PHPDoc blocks.
    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class,
        [
            'allow_mixed' => true, 'allow_unused_params' => true
        ]); // Removes `@param`, `@return` and `@var` tags that don\'t provide any useful information.
    $ecsConfig->rule(PhpdocAddMissingParamAnnotationFixer::class); // PHPDoc should contain `@param` for all params.
    $ecsConfig->ruleWithConfiguration(PhpdocAlignFixer::class,
        [
            'align' => 'left', 'tags' => ['method', 'param', 'property', 'return', 'throws', 'type', 'var']
        ]); // All items of the given phpdoc tags must be aligned properly.
    $ecsConfig->rule(PhpdocAnnotationWithoutDotFixer::class); // PHPDoc annotation descriptions should not be a sentence.
    $ecsConfig->rule(PhpdocIndentFixer::class); // Docblocks should have the same indentation as the documented subject.
    $ecsConfig->rule(PhpdocInlineTagNormalizerFixer::class); // Fixes PHPDoc inline tags.
    $ecsConfig->rule(PhpdocLineSpanFixer::class); // Changes doc blocks from single to multi line, or reversed. Works for class constants, properties and methods only.
    $ecsConfig->rule(PhpdocNoAccessFixer::class); // `@access` annotations should be omitted from PHPDoc.
    $ecsConfig->rule(PhpdocNoAliasTagFixer::class); // No alias PHPDoc tags should be used.
    $ecsConfig->rule(PhpdocNoEmptyReturnFixer::class); // `@return void` and `@return null` annotations should be omitted from PHPDoc.
    $ecsConfig->rule(PhpdocNoPackageFixer::class); // `@package` and `@subpackage` annotations should be omitted from PHPDoc.
    $ecsConfig->rule(PhpdocNoUselessInheritdocFixer::class); // Classy that does not inherit must not have `@inheritdoc` tags.
    $ecsConfig->rule(PhpdocOrderByValueFixer::class); // Order phpdoc tags by value.
    $ecsConfig->rule(PhpdocOrderFixer::class); // Annotations in PHPDoc should be ordered so that `@param` annotations come first, then `@throws` annotations, then `@return` annotations.
    $ecsConfig->rule(PhpdocReturnSelfReferenceFixer::class); // The type of `@return` annotations of methods returning a reference to itself must the configured one.
    $ecsConfig->rule(PhpdocScalarFixer::class); // Scalar types should always be written in the same form. `int` not `integer`, `bool` not `boolean`, `float` not `real` or `double`.
    $ecsConfig->rule(PhpdocSeparationFixer::class); // Annotations in PHPDoc should be grouped together so that annotations of the same type immediately follow each other, and annotations of a different type are separated by a single blank line.
    $ecsConfig->rule(PhpdocSingleLineVarSpacingFixer::class); // Single line `@var` PHPDoc should have proper spacing.
    $ecsConfig->rule(PhpdocSummaryFixer::class); // PHPDoc summary should end in either a full stop, exclamation mark, or question mark.
    $ecsConfig->rule(PhpdocTagCasingFixer::class); // Fixes casing of PHPDoc tags.
    $ecsConfig->rule(PhpdocTagTypeFixer::class); // Forces PHPDoc tags to be either regular annotations or inline.
    $ecsConfig->rule(PhpdocTrimConsecutiveBlankLineSeparationFixer::class); // Removes extra blank lines after summary and after description in PHPDoc.
    $ecsConfig->rule(PhpdocTrimFixer::class); // PHPDoc should start and end with content, excluding the very first and last line of the docblocks.
    $ecsConfig->rule(PhpdocTypesFixer::class); // The correct case must be used for standard PHP types in PHPDoc.
    $ecsConfig->rule(PhpdocTypesOrderFixer::class); // Sorts PHPDoc types.
    $ecsConfig->rule(PhpdocVarAnnotationCorrectOrderFixer::class); // `@var` and `@type` annotations must have type and name in the correct order.
    $ecsConfig->rule(PhpdocVarWithoutNameFixer::class); // `@var` and `@type` annotations of classy properties should not contain the name.

    // PhpCsFixer\Fixer\PhpTag
    $ecsConfig->rule(BlankLineAfterOpeningTagFixer::class); // Ensure there is no code on the same line as the PHP open tag and it is followed by a blank line.
    $ecsConfig->rule(EchoTagSyntaxFixer::class); // Replaces short-echo `<?=` with long format `<?php echo`/`<?php print` syntax, or vice-versa.
    $ecsConfig->rule(FullOpeningTagFixer::class); // PHP code must use the long `<?php` tags or short-echo `<?=` tags and not other tag variations.
    $ecsConfig->rule(LinebreakAfterOpeningTagFixer::class); // Ensure there is no code on the same line as the PHP open tag.
    $ecsConfig->rule(NoClosingTagFixer::class); // The closing tag must be omitted from files containing only PHP.

    // PhpCsFixer\Fixer\PhpUnit
    $ecsConfig->rule(PhpUnitConstructFixer::class); // PHPUnit assertion method calls like `->assertSame(true, $foo)` should be written with dedicated method like `->assertTrue($foo)`.
    $ecsConfig->rule(PhpUnitDedicateAssertFixer::class); // PHPUnit assertions like `assertInternalType`, `assertFileExists`, should be used over `assertTrue`.
    $ecsConfig->rule(PhpUnitDedicateAssertInternalTypeFixer::class); // PHPUnit assertions like `assertIsArray` should be used over `assertInternalType`.
    $ecsConfig->rule(PhpUnitFqcnAnnotationFixer::class); // PHPUnit annotations should be a FQCNs including a root namespace.
    $ecsConfig->rule(PhpUnitInternalClassFixer::class); // All PHPUnit test classes should be marked as internal.
    $ecsConfig->rule(PhpUnitMethodCasingFixer::class); // Enforce camel (or snake) case for PHPUnit test methods, following configuration.
    $ecsConfig->rule(PhpUnitMockFixer::class); // Usages of `->getMock` and `->getMockWithoutInvokingTheOriginalConstructor` methods MUST be replaced by `->createMock` or `->createPartialMock` methods.
    $ecsConfig->rule(PhpUnitMockShortWillReturnFixer::class); // Usage of PHPUnit\'s mock e.g. `->will($this->returnValue(..))` must be replaced by its shorter equivalent such as `->willReturn(...)`.
    $ecsConfig->rule(PhpUnitNamespacedFixer::class); // PHPUnit classes MUST be used in namespaced version, e.g. `\PHPUnit\Framework\TestCase` instead of `\PHPUnit_Framework_TestCase`.
    $ecsConfig->rule(PhpUnitNoExpectationAnnotationFixer::class); // Usages of `@expectedException*` annotations MUST be replaced by `$ecsConfig->ruleExpectedException*` methods.
    $ecsConfig->rule(PhpUnitSetUpTearDownVisibilityFixer::class); // Changes the visibility of the `setUp()` and `tearDown()` functions of PHPUnit to `protected`, to match the PHPUnit TestCase.
    $ecsConfig->rule(PhpUnitSizeClassFixer::class); // All PHPUnit test cases should have `@small`, `@medium` or `@large` annotation to enable run time limits.
    $ecsConfig->rule(PhpUnitStrictFixer::class); // PHPUnit methods like `assertSame` should be used instead of `assertEquals`.
    $ecsConfig->rule(PhpUnitTestAnnotationFixer::class); // Adds or removes @test annotations from tests, following configuration.
    $ecsConfig->rule(PhpUnitTestCaseStaticMethodCallsFixer::class); // Calls to `PHPUnit\Framework\TestCase` static methods must all be of the same type, either `$this->`, `self::` or `static::`.

    // PhpCsFixer\Fixer\ReturnNotation
    $ecsConfig->rule(NoUselessReturnFixer::class); // There should not be an empty `return` statement at the end of a function.
    $ecsConfig->rule(SimplifiedNullReturnFixer::class); // A return statement wishing to return `void` should not return `null`.

    // PhpCsFixer\Fixer\Semicolon
    $ecsConfig->rule(MultilineWhitespaceBeforeSemicolonsFixer::class); // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
    $ecsConfig->rule(NoEmptyStatementFixer::class); // Remove useless (semicolon) statements.
    $ecsConfig->rule(NoSinglelineWhitespaceBeforeSemicolonsFixer::class); // Single-line whitespace before closing semicolon are prohibited.
    $ecsConfig->rule(SemicolonAfterInstructionFixer::class); // Instructions must be terminated with a semicolon.
    $ecsConfig->rule(SpaceAfterSemicolonFixer::class); // Fix whitespace after a semicolon.

    // PhpCsFixer\Fixer\Strict
    $ecsConfig->rule(DeclareStrictTypesFixer::class); // Force strict types declaration in all files. Requires PHP >= 7.0.
    $ecsConfig->rule(StrictComparisonFixer::class); // Comparisons should be strict.
    $ecsConfig->rule(StrictParamFixer::class); // Functions should be used with `$strict` param set to `true`.

    // PhpCsFixer\Fixer\StringNotation
    $ecsConfig->rule(EscapeImplicitBackslashesFixer::class); // Escape implicit backslashes in strings and heredocs to ease the understanding of which are special chars interpreted by PHP and which not.
    $ecsConfig->rule(ExplicitStringVariableFixer::class); // Converts implicit variables into explicit ones in double-quoted strings or heredoc syntax.
    $ecsConfig->rule(HeredocToNowdocFixer::class); // Convert `heredoc` to `nowdoc` where possible.
    $ecsConfig->rule(NoBinaryStringFixer::class); // There should not be a binary flag before strings.
    $ecsConfig->rule(NoTrailingWhitespaceInStringFixer::class); // There must be no trailing whitespace in strings.
    $ecsConfig->rule(SimpleToComplexStringVariableFixer::class); // Converts explicit variables in double-quoted strings and heredoc syntax from simple to complex format (`${` to `{$`).
    $ecsConfig->rule(SingleQuoteFixer::class); // 'Convert double quotes to single quotes for simple strings.
    $ecsConfig->rule(StringLineEndingFixer::class); // All multi-line strings must use correct line ending.

    // PhpCsFixer\Fixer\Whitespace
    $ecsConfig->rule(ArrayIndentationFixer::class); // Each element of an array must be indented exactly once.
    $ecsConfig->rule(BlankLineBeforeStatementFixer::class); // An empty line feed must precede any configured statement.
    $ecsConfig->rule(CompactNullableTypehintFixer::class); // Remove extra spaces in a nullable typehint.
    $ecsConfig->rule(HeredocIndentationFixer::class); // Heredoc/nowdoc content must be properly indented. Requires PHP >= 7.3.
    $ecsConfig->rule(IndentationTypeFixer::class); // Code must use configured indentation type.
    $ecsConfig->rule(LineEndingFixer::class); // All PHP files must use same line ending.
    $ecsConfig->rule(MethodChainingIndentationFixer::class); // Method chaining MUST be properly indented. Method chaining with different levels of indentation is not supported.
    $ecsConfig->ruleWithConfiguration(NoExtraBlankLinesFixer::class, [
        'tokens' => [
            'curly_brace_block', 'extra', 'parenthesis_brace_block', 'square_brace_block', 'throw', 'use'
        ]
    ]); // Removes extra blank lines and/or blank lines following configuration.
    $ecsConfig->rule(NoSpacesAroundOffsetFixer::class); // There MUST NOT be spaces around offset braces.
    $ecsConfig->rule(NoSpacesInsideParenthesisFixer::class); // There MUST NOT be a space after the opening parenthesis. There MUST NOT be a space before the closing parenthesis.
    $ecsConfig->rule(NoTrailingWhitespaceFixer::class); // Remove trailing whitespace at the end of non-blank lines.
    $ecsConfig->rule(NoWhitespaceInBlankLineFixer::class); // Remove trailing whitespace at the end of blank lines.
    $ecsConfig->rule(SingleBlankLineAtEofFixer::class); // A PHP file without end tag must always end with a single empty line feed.

    // Symplify\CodingStandard\Fixer\Annotation
    $ecsConfig->rule(RemovePHPStormAnnotationFixer::class); // Remove "Created by PhpStorm" annotations.

    // Symplify\CodingStandard\Fixer\ArrayNotation
    $ecsConfig->rule(ArrayOpenerAndCloserNewlineFixer::class); // Indexed PHP array opener [ and closer ] must be on own line.
    $ecsConfig->rule(StandaloneLineInMultilineArrayFixer::class); // Indexed arrays must have 1 item per line.

    // Symplify\CodingStandard\Fixer\Commenting
    $ecsConfig->rule(ParamReturnAndVarTagMalformsFixer::class); // Fixes @param, @return, @var and inline @var annotations broken formats.
    $ecsConfig->rule(RemoveUselessDefaultCommentFixer::class); // Remove useless PHPStorm-generated to do comments, redundant "Class XY" or "gets service" comments etc.

    // Symplify\CodingStandard\Fixer\LineLength
    $ecsConfig->rule(LineLengthFixer::class); // Array items, method parameters, method call arguments, new arguments should be on same/standalone line to fit line length.

    // Symplify\CodingStandard\Fixer\Spacing
    $ecsConfig->rule(SpaceAfterCommaHereNowDocFixer::class); // Add space after nowdoc and heredoc keyword, to prevent bugs on PHP 7.2 and lower.
    $ecsConfig->rule(StandaloneLinePromotedPropertyFixer::class);// Promoted property should be on standalone line.

    // Symplify\CodingStandard\Fixer\Strict
    $ecsConfig->rule(BlankLineAfterStrictTypesFixer::class); // Require strict type declarations to be followed by empty line.

    // SlevomatCodingStandard\Sniffs
    $ecsConfig->rule(RequireShortTernaryOperatorSniff::class); // Require usage of short ternary operator.
    $ecsConfig->rule(UnusedInheritedVariablePassedToClosureSniff::class); // Dissable unused variables to closures.
    $ecsConfig->rule(RequireCombinedAssignmentOperatorSniff::class); // Require use of "%s" operator instead of "=" and "%s".
    $ecsConfig->rule(DisallowDirectMagicInvokeCallSniff::class); // Disallow direct calls of __invoke().
    $ecsConfig->rule(UselessSemicolonSniff::class); // Remove useless semicolons.
    $ecsConfig->rule(UselessVariableSniff::class); // Remove useless variables.
};
