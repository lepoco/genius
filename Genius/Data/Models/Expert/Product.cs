// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Genius.Data.Models.Expert
{
    public class Product
    {
        public int Id { get; set; }

        [Required]
        [ForeignKey("System")]
        public int SystemId { get; set; }

        [Required]
        public string Name { get; set; }

        public string Description { get; set; }

        public string Notes { get; set; }
    }
}
