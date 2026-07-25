import re

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # The block to wrap:
    #       // Icon Picker Search (Amenity)
    #       const amenityIconSearch = document.getElementById('amenityIconSearch');
    #       ...
    #       // Icon Selection (Booking Feature)
    #       document.querySelectorAll('.bf-icon-option').forEach(opt => {
    #           opt.addEventListener('click', function() {
    # ...
    #           });
    #       });

    # Find the block starting from Icon Picker Search (Amenity) to the end of Icon Selection (Booking Feature)
    # Since there are multiple blocks, it's easier to just wrap them in a DOMContentLoaded event.
    
    # Let's replace the whole block by finding it.
    block_start_marker = "// Icon Picker Search (Amenity)"
    block_end_marker = "});\n      });"
    
    start_idx = content.find(block_start_marker)
    if start_idx == -1:
        return
        
    # We find the specific end of the bf-icon-option loop.
    # We will search for // Icon Selection (Booking Feature)
    bf_start = content.find("// Icon Selection (Booking Feature)", start_idx)
    # Find the end of the forEach loop
    bf_end = content.find("});\n      });", bf_start) + len("});\n      });")
    
    # We need to wrap everything from start_idx to bf_end inside document.addEventListener('DOMContentLoaded', function() { ... });
    
    original_block = content[start_idx:bf_end]
    
    # Create the wrapped block
    wrapped_block = "document.addEventListener('DOMContentLoaded', function() {\n" + original_block + "\n      });"
    
    # Replace in content
    new_content = content[:start_idx] + wrapped_block + content[bf_end:]
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)
        print(f"Fixed {filepath}")

fix_file('p:/xampp83/htdocs/michigan-explorer/resources/views/new_content/admin/hotels/create.blade.php')
fix_file('p:/xampp83/htdocs/michigan-explorer/resources/views/new_content/admin/hotels/edit.blade.php')
