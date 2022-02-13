// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Models.Expert;
using Microsoft.EntityFrameworkCore;

namespace Genius.Data.Contexts
{
    /// <summary>
    /// Abstraction of the database context;
    /// </summary>
    public interface IExpertContext : IDbContext
    {
        /// <summary>
        /// Contains a list of all expert systems.
        /// </summary>
        public DbSet<Models.Expert.System> Systems { get; }

        /// <summary>
        /// Contains a list of all products, i.e. the results of the program operation.
        /// </summary>
        public DbSet<Product> Products { get; }

        /// <summary>
        /// Contains a list of all conditions, i.e. elements that determine what product we will get.
        /// </summary>
        public DbSet<Condition> Conditions { get; }

        /// <summary>
        /// Contains a list of all relationships, i.e. dependencies between the Products and the Conditions.
        /// </summary>
        public DbSet<Relation> Relations { get; }
    }
}
