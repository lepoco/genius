// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.OAuth.Data.Models.System;
using Microsoft.EntityFrameworkCore;

namespace Genius.OAuth.Data.Contexts
{
    /// <summary>
    /// Represents the database responsible for the application logic.
    /// </summary>
    /// Add-Migration InitialCreate -OutputDir Data/Migrations/System -Context SystemContext
    /// Update-Database -Context SystemContext
    public class SystemContext : DbContext
    {
        public DbSet<User> Users { get; set; }

        public DbSet<Statistic> Statistics { get; set; }

        public SystemContext(DbContextOptions<SystemContext> options) : base(options)
        {
        }
    }
}
