# Nicescrollr Architecture Documentation

## 🏗️ **Modern Architecture Overview**

This plugin has been refactored to follow industry-standard best practices, incorporating modern design patterns and clean architecture principles.

## **Directory Structure**

```
nicescrollr/
├── src/                          # PSR-4 autoloaded source code
│   ├── Core/                     # Core functionality
│   │   ├── Contracts/            # Interfaces (Dependency Inversion)
│   │   │   ├── ConfigInterface.php
│   │   │   ├── RepositoryInterface.php
│   │   │   ├── ServiceInterface.php
│   │   │   └── ValidatorInterface.php
│   │   ├── Config/               # Configuration classes
│   │   │   └── ScrollbarConfig.php
│   │   ├── Repositories/         # Data access layer
│   │   │   └── OptionsRepository.php
│   │   ├── Services/             # Business logic layer
│   │   │   └── OptionsService.php
│   │   └── Support/              # Utilities & helpers
│   │       ├── Container.php     # DI Container
│   │       └── Validator.php     # Input validation
│   ├── Admin/                    # Admin functionality
│   │   ├── Controllers/          # Admin controllers
│   │   └── Views/                # Admin templates
│   ├── Frontend/                 # Public-facing functionality
│   │   └── Controllers/          # Frontend controllers
│   └── Shared/                   # Shared components
│       └── Assets/               # Shared assets
├── assets/                       # Modern frontend assets
│   ├── js/
│   │   ├── modules/              # ES6 modules
│   │   │   ├── ScrollbarManager.js
│   │   │   ├── BackTopButton.js
│   │   │   └── utils/
│   │   │       ├── DeviceDetector.js
│   │   │       └── DOMUtils.js
│   │   └── nicescrollr.js        # Main entry point
│   ├── css/
│   └── vendor/
├── admin/                        # Legacy (being phased out)
├── public/                       # Legacy (being phased out)
└── vendor/                       # External libraries
```

## **Design Patterns Implemented**

### 1. **Repository Pattern**
Separates data access logic from business logic.

```php
// Old way (mixing concerns)
$options = get_option( 'nicescrollr_options' );
$options['frontend']['enabled'] = true;
update_option( 'nicescrollr_options', $options );

// New way (clean separation)
$repository = nicescrollr( OptionsRepository::class );
$repository->save( 'frontend.enabled', true );
```

**Benefits:**
- Testable (can mock repository)
- Cacheable
- Database-agnostic
- Supports dot notation for nested keys

### 2. **Service Layer Pattern**
Encapsulates business logic and orchestrates operations.

```php
// Using the service
$service = nicescrollr( OptionsService::class );
$options = $service->getOptions( 'frontend' );
$service->update( 'frontend.cursorcolor', 'rgba(255, 0, 0, 1)' );
```

**Benefits:**
- Validates data before saving
- Handles complex operations
- Centralizes business rules
- Easy to extend

### 3. **Dependency Injection**
Automatic dependency resolution via container.

```php
// Container automatically resolves dependencies
$container = nicescrollr_container();

// Automatic resolution
$service = $container->make( OptionsService::class );

// Or use helper
$service = nicescrollr( OptionsService::class );
```

**Benefits:**
- Loose coupling
- Easy testing (inject mocks)
- Flexible configuration
- Better code organization

### 4. **Strategy Pattern** (Validation)
Flexible validation rules without conditional bloat.

```php
// Define validation rules in configuration
'cursoropacitymin' => [
    'type' => 'number',
    'validation' => [ 'numeric', 'min:0', 'max:1' ],
],

// Validator applies strategies dynamically
$validator->validate( $value, [ 'numeric', 'min:0', 'max:1' ] );
```

**Benefits:**
- Extensible
- Reusable
- Clear separation of concerns
- Easy to add new rules

### 5. **Interface Segregation**
Small, focused interfaces instead of monolithic classes.

```php
interface RepositoryInterface {
    public function all(): array;
    public function find( string $key );
    public function save( string $key, $value ): bool;
    public function delete( string $key ): bool;
    public function exists( string $key ): bool;
}
```

**Benefits:**
- SOLID principles
- Easy to implement
- Clear contracts
- Better documentation

## **Modern JavaScript Architecture**

### ES6+ Modules
Replaced jQuery-dependent code with modern vanilla JavaScript.

```javascript
// Old way (jQuery-dependent)
function Nicescrollr() {
    this.body = $('body');
}

// New way (ES6 class with private fields)
export class ScrollbarManager {
    #config;
    #elements;

    constructor( config = {} ) {
        this.#config = this.#normalizeConfig( config );
        this.#elements = {
            body: document.body,
            html: document.documentElement,
        };
    }
}
```

**Features:**
- **Private fields** (`#property`)
- **ES6 classes** with proper encapsulation
- **Module imports** for code splitting
- **Arrow functions** for cleaner syntax
- **Destructuring** for cleaner code
- **Template literals** for readable strings

### Module Structure

```javascript
import { ScrollbarManager } from './modules/ScrollbarManager.js';
import { BackTopButton } from './modules/BackTopButton.js';

// Initialize
const app = new Nicescrollr( config );
app.init();
```

**Benefits:**
- **No jQuery dependency** (smaller bundle)
- **Tree-shakeable** (only import what you need)
- **Better performance** (native browser APIs)
- **Type-safe** (compatible with TypeScript)
- **Modern** (uses latest JavaScript features)

## **Usage Examples**

### **PHP Usage**

```php
// Get the DI container
$container = nicescrollr_container();

// Get service instance
$options = nicescrollr( OptionsService::class );

// Get options for frontend
$frontend_options = $options->getOptions( 'frontend' );

// Update a specific option
$options->update( 'frontend.cursorcolor', 'rgba(255, 0, 0, 1)' );

// Reset to defaults
$options->reset( 'frontend' );

// Validate before saving
$validator = nicescrollr( ValidatorInterface::class );
$is_valid = $validator->validate( '#FF0000', [ 'color' ] );
```

### **JavaScript Usage**

```javascript
// Configuration is passed via wp_localize_script
const config = window.Nsr_Options;

// Create instances
import { ScrollbarManager } from './modules/ScrollbarManager.js';
const scrollbar = new ScrollbarManager( config );
scrollbar.init();

// Refresh scrollbar
scrollbar.refresh();

// Destroy
scrollbar.destroy();
```

## **Migration Guide**

### **From Old to New**

#### Options Management

```php
// ❌ Old way
$Options = new Nsr_Options( 'nicescrollr' );
$options = $Options->get_options();

// ✅ New way
$service = nicescrollr( OptionsService::class );
$options = $service->getOptions( 'frontend' );
```

#### Validation

```php
// ❌ Old way (deprecated strip_tags)
$clean = strip_tags( stripslashes( $value ) );

// ✅ New way (modern sanitization)
$validator = nicescrollr( ValidatorInterface::class );
$clean = $validator->sanitize( $value, 'text' );
```

#### Configuration

```php
// ❌ Old way (1,315 line monolith)
private function basic_settings() {
    return array( /* 150 lines of config */ );
}

// ✅ New way (clean, focused config class)
$config = nicescrollr( ConfigInterface::class );
$schema = $config->schema();
$defaults = $config->defaults( 'frontend' );
```

## **Performance Improvements**

### PHP

1. **Lazy Loading**: Services only instantiated when needed
2. **Caching**: Repository caches options in memory
3. **Validation**: Fails fast with early returns
4. **Autoloading**: PSR-4 only loads used classes

### JavaScript

1. **No jQuery**: ~30KB smaller bundle size
2. **ES6 Modules**: Tree-shaking removes unused code
3. **Throttling**: Scroll events optimized with throttle
4. **Passive Listeners**: Better scroll performance
5. **Mutation Observer**: Efficient DOM change detection

## **Testing**

The new architecture is designed for testability:

```php
// Example unit test
public function test_options_repository_can_save() {
    $repository = new OptionsRepository();

    $result = $repository->save( 'frontend.enabled', true );

    $this->assertTrue( $result );
    $this->assertTrue( $repository->exists( 'frontend.enabled' ) );
}

// Example with mocking
public function test_service_validates_before_saving() {
    $validator = $this->createMock( ValidatorInterface::class );
    $validator->method( 'validate' )->willReturn( false );

    $service = new OptionsService( $repository, $config, $validator );

    $result = $service->update( 'frontend.enabled', 'invalid' );

    $this->assertFalse( $result );
}
```

## **Extensibility**

### Adding Custom Validators

```php
// Register custom validator
add_filter( 'nicescrollr_validators', function( $validators ) {
    $validators['my_custom_rule'] = function( $value, $params ) {
        // Custom validation logic
        return true;
    };
    return $validators;
} );
```

### Adding Custom Configuration

```php
// Extend configuration
add_filter( 'nicescrollr_config_schema', function( $schema ) {
    $schema['my_option'] = [
        'type' => 'text',
        'label' => 'My Custom Option',
        'validation' => [ 'required', 'min:3' ],
    ];
    return $schema;
} );
```

### Hooking into Services

```php
// After container bootstrap
add_action( 'nicescrollr_bootstrapped', function( $container ) {
    $service = $container->make( OptionsService::class );

    // Your custom logic
} );
```

## **Best Practices**

1. **Always use the container** for dependency resolution
2. **Type-hint interfaces** not concrete classes
3. **Keep services focused** on single responsibility
4. **Validate all input** before persisting
5. **Use repositories** for all data access
6. **Document public APIs** with PHPDoc
7. **Follow PSR standards** for code style

## **Benefits Summary**

### Code Quality
- ✅ SOLID principles throughout
- ✅ Separation of concerns
- ✅ Single Responsibility Principle
- ✅ Dependency Inversion
- ✅ Interface Segregation

### Maintainability
- ✅ Modular architecture
- ✅ Clear file organization
- ✅ Consistent naming
- ✅ Comprehensive documentation

### Testability
- ✅ Dependency injection
- ✅ Mockable interfaces
- ✅ Isolated components
- ✅ Pure functions

### Performance
- ✅ Lazy loading
- ✅ Caching
- ✅ Optimized JavaScript
- ✅ Smaller bundle size

### Security
- ✅ Input validation
- ✅ Modern sanitization
- ✅ Type safety
- ✅ Escaping output

---

**Version:** 1.0.0
**Last Updated:** 2025
**Author:** Demis Patti with architectural improvements by Claude
